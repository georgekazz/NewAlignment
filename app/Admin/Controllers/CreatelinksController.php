<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Controllers\AdminController;
use EasyRdf\Graph;
use App\Models\File;
use DB;
use App\Models\RDFTrait;

class CreatelinksController extends AdminController
{
    use RDFTrait;
    protected $title = 'Create Links';
    public function grid()
    {
        session_start();

        $url = $_SERVER['REQUEST_URI'];
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', $path);
        $project = end($parts);
        
        $project = Project::find($project);
        $this->cacheOntologies();
        $nameSource = implode("_", ["project", $project->id, "source", $project->source->id, '']);
        $nameTarget = implode("_", ["project", $project->id, "target", $project->target->id, '']);
        $filenameS = 'json_serializer/' . $nameSource . ".json";
        $filenameT = 'json_serializer/' . $nameTarget . ".json";
        $_SESSION["source_json"] = $filenameS;
        $_SESSION["target_json"] = $filenameT;
        $groups = $this->getGroups();
        return view(
            'createlinks',
            [
                'project' => $project,
                'groups' => $groups,
            ]
        );
    }

    private function cacheOntologies()
    {
        if (Cache::has('ontologies_graph')) {
            return "Ontologies already on Cache";
        } else {
            $graph1 = new Graph;
            $graph1->parseFile(storage_path('app/ontologies/owl.rdf'));

            $graph2 = new Graph;
            $graph2->parseFile(storage_path('app/ontologies/rdfs.rdf'));

            $graph3 = new Graph;
            $graph3->parseFile(storage_path('app/ontologies/skos.rdf'));

            $graph1_2 = $this->mergeGraphs($graph1, $graph2);
            $merged_graph = $this->mergeGraphs($graph1_2, $graph3);

            Cache::forever('ontologies_graph', $merged_graph);
            return "Ontologies Cached";
        }
    }

    public function json_serializer($file)
    {
        if (Storage::disk('public')->exists('json_serializer/' . $file)) {

            $jsonfile = Storage::disk('public')->json('json_serializer/' . $file);

        } else {

            $newfile = explode("_", pathinfo($file, PATHINFO_FILENAME));
            $filename = $this->D3_convert(Project::find($newfile[1]), $newfile[2], $newfile[4]);

            // Επιστροφή του JSON αρχείου
            $jsonfile = Storage::disk('public')->get($filename);

        }
        return (new Response($jsonfile, 200))
            ->header('Content-Type', 'application/json');
    }

    public function infobox(Request $request)
    {
        $project = Project::find($request->project_id);
        $dump = $request->dump;
        $file = $project->$dump;
        $graph_name = $file->id . "_graph";
        $graph = Cache::get($graph_name);
        if ($graph) {
            logger("Τα δεδομένα RDF βρέθηκαν στο cache για το έργο {$project->id}");
        } else {
            logger("Τα δεδομένα RDF ΔΕΝ βρέθηκαν στο cache για το έργο {$project->id}. Θα δημιουργηθεί νέο.");
        }   
        $uri = urldecode($request["uri"]);
        $result = $graph->dumpResource($uri, "html");
        return $result;
    }

    public static function mergeGraphs(Graph $graph1, Graph $graph2)
    {
        try {
            $data1 = $graph1->toRdfPhp();
            $data2 = $graph2->toRdfPhp();
            $merged = array_merge_recursive($data1, $data2);
            unset($data1, $data2);
            return new Graph('urn:easyrdf:merged', $merged, 'php');
        } catch (\Exception $e) {
            logger("Σφάλμα κατά τη συνένωση των γραφημάτων: " . $e->getMessage());
            throw $e;
        }
    }

    public function short_infobox(Request $request)
    {
        $project = Project::find($request->project_id);
        $dump = $request->dump;
        $file = $project->$dump;
        $graph_name = $file->id . "_graph";
        $graph = Cache::get($graph_name);
        $uri = urldecode($request["uri"]);
        $prefLabel = $this->label($graph, $uri);
        $collapsed = isset($request->collapsed) ? ($request->collapsed === "true" ? "plus" : "minus") : "plus";
        $details = CreatelinksController::infobox($request);
        return view('createlinks.partials.info', ['header' => $prefLabel, 'dump' => $request["dump"], "details" => $details, "collapsed" => $collapsed]);
    }

    public function comparison(Request $request, Project $project)
    {
        $iri = urldecode($request['url']);
        $graph_name = $project->target->id . "_graph";
        $graph = Cache::get($graph_name);
        $scores = Cache::get("scores_graph_project" . $project->id);
        $results = $scores->resourcesMatching("http://knowledgeweb.semanticweb.org/heterogeneity/alignment#entity1", new \EasyRdf\Resource($iri));
        $candidates = [];
        foreach ($results as $result) {
            $target = $scores->get($result, new \EasyRdf\Resource("http://knowledgeweb.semanticweb.org/heterogeneity/alignment#entity2"));
            $score = $scores->get($result, new \EasyRdf\Resource("http://knowledgeweb.semanticweb.org/heterogeneity/alignment#measure"))->getValue();
            $label = $this->label($graph, $target);
            $class = ($score < 0.3) ? "low" : (($score >= 0.3 && $score < 0.8) ? "medium" : "high");
            $candidate = [
                "target" => $target,
                "score" => $score,
                "label" => $label,
                "class" => $class,
            ];
            $candidates[] = $candidate;
        }
        $candidates = collect($candidates); // Μετατροπή σε συλλογή
        return view('createlinks.partials.comparison', compact('candidates'));
    }

    public function getGroups()
    {
        $user = Auth::guard('admin')->user();
        $select = DB::table('link_types')->select('group as option')
            ->where('public', '=', 'true')
            ->orWhere('user_id', '=', $user)
            ->distinct()
            ->get();
        return $select;
    }

    private function parseGraph(File $file)
    {
        try {
            $graph = new Graph;

            // Αφαίρεση .rdf ή .ttl αν υπάρχει
            $filePath = storage_path('app/' . preg_replace('/\.(rdf|ttl)$/', '', $file->resource) . '.nt');

            if (!file_exists($filePath)) {
                logger(" Το αρχείο NT ΔΕΝ βρέθηκε: " . $filePath);
                abort(404, "Το αρχείο NT δεν βρέθηκε: " . $filePath);
            }

            // Έλεγχος αν το αρχείο είναι αναγνώσιμο
            if (!is_readable($filePath)) {
                logger(" Το αρχείο υπάρχει αλλά δεν είναι αναγνώσιμο: " . $filePath);
                abort(500, "Το αρχείο υπάρχει αλλά δεν είναι αναγνώσιμο: " . $filePath);
            }

            // Ανάγνωση αρχείου
            logger(" Το αρχείο βρέθηκε! Διαβάζω από: " . $filePath);
            $graph->parseFile($filePath, 'ntriples');
            logger(" Επιτυχής ανάγνωση RDF: " . $filePath);

            Cache::forever($file->id . "_graph", $graph);

            return $graph;
        } catch (\Exception $ex) {
            logger(" Σφάλμα κατά την ανάγνωση RDF: " . $ex->getMessage());
            abort(500, "Σφάλμα κατά την ανάγνωση RDF: " . $ex->getMessage());
        }
    }


    public function D3_convert(Project $project, $dump, $orderBy = null)
    {
        $file = $project->$dump; 

        $filePath = storage_path('app/' . $file->resource);

        if (!file_exists($filePath)) {
            dd("Το αρχείο δεν υπάρχει στη διαδρομή: " . $filePath);
        }

        /*
         * Read the graph
         */
        $graph = $this->parseGraph($file);
        /*
         * Get the parent node
         */
        $root = 'http://www.w3.org/2004/02/skos/core#ConceptScheme';
        $firstLevelPath = "^skos:topConceptOf";
        $parents = $graph->allOfType($root);
        /*
         * Iterate through all parents
         */
        $JSON = [];
        if ($dump === "source") {
            $score = Cache::get("scores_graph_project" . $project->id);
        } else {
            $score = null;
        }
        foreach ($parents as $parent) {
            /*
             * Create Root Entry
             */
            $name = $this->label($graph, $parent);
            $JSON['name'] = "$name";
            $JSON['url'] = urlencode($parent);
            $children = $this->find_children($graph, $firstLevelPath, $parent, $orderBy, $score);
            $JSON['children'] = $orderBy === null ? $children : collect($children)->sortBy($orderBy)->values()->toArray();
        }
        logger("Αναγνώριση δεδομένων για το JSON: " . json_encode($JSON));

        /*
         * create JSON file
         */
        $name = implode("_", ["project", $project->id, $dump, $file->id, $orderBy]);
        $filename = 'json_serializer/' . $name . ".json";
        Storage::disk('public')->put($filename, json_encode($JSON));
        return $filename;
    }

    function find_children(Graph $graph, $hierarchic_link, $parent_url, $orderBy = null, $score = null)
    {
        $children = $graph->allResources($parent_url, $hierarchic_link);
        $counter = 0;
        $myJSON = [];
        $link = "skos:narrower";
        $inverseLink = "^skos:broader";
        foreach ($children as $child) {
            $name = $this->label($graph, $child);
            $myJSON[]["name"] = "$name";
            if($score !== null){
                $suggestions = count($score->resourcesMatching("http://knowledgeweb.semanticweb.org/heterogeneity/alignment#entity1", $child));
            }
            else{
                $suggestions = 0;
            }
            
            $myJSON[$counter]['suggestions'] = $suggestions;
            $myJSON[$counter]['url'] = urlencode($child);
            $children = $this->find_children($graph, $link, $child, $orderBy, $score);
            if (sizeOf($children) == 0){
                $children = $this->find_children($graph, $inverseLink, $child, $orderBy, $score);
            }

            $myJSON[$counter]['children'] = $orderBy === null ? $children : collect($children)->sortBy($orderBy)->values()->toArray();
            $counter++;
        }
        return $myJSON;
    }


}
