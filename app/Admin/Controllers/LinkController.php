<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Arr;
use App\Models\Link;
use Yajra\Datatables\Datatables;
use Cache;
use Carbon\Carbon;
use EasyRdf\Graph;
use App\Models\Import;
use EasyRdf\Format;
use EasyRdf\Resource as EasyRdf_Resource;
use EasyRdf\RdfNamespace;
use Illuminate\Contracts\Auth\Authenticatable;
use OpenAdmin\Admin\Layout\Content;
use Illuminate\Support\Str;

class LinkController extends AdminController
{
    public function grid()
    {
        $user = Auth::guard('admin')->user();
        $projects = Project::where("user_id", "=", $user->id)
            ->orWhere("public", "=", TRUE)
            ->get();
        $select = [];
        foreach ($projects as $project) {

            $key = $project->id;
            $value = $project->name;
            $select = Arr::add($select, $key, $value);
        }

        return view('mylinks', [
            "user" => $user,
            "projects" => $projects,
            "select" => $select,
        ]);
    }

    public function project_links(Request $request)
    {
        session_start();
        logger("dddd");
        $project = Project::find($request->project_id);
        return view('links.link_table', ["project" => $project]);
    }

    public function connected(Request $request)
    {
        session_start();
        $project = Project::find($request->project_id);
        $type = $request->type;
        $links = $project->links;
        $connected = array();
        $entity = $type . '_entity';
        foreach ($links as $link) {
            array_push($connected, $link->$entity);
        }
        $connected = array_values(array_unique($connected, SORT_REGULAR));
        return json_encode($connected);
    }

    public function create(Content $content)
    {
        $user = Auth::guard('admin')->user();
        $input = request()->all();
        $project = Project::find($input['project_id']);
        $sourceId = $project->source_id; // Υποθέτουμε ότι το project έχει το πεδίο source_id
        $previous = Link::where('project_id', '=', $input['project_id'])
            ->where('source_entity', '=', $input['source'])
            ->where('target_entity', '=', $input['target'])
            ->where('link_type', '=', $input['link_type'])
            ->first();

        if ($previous == null) {
            $link = new Link;
            $link->session_id = 1; // Υποθέτουμε ότι αυτή είναι η τιμή του session_id
            $link->updated_at = now();
            $link->created_at = now();
            $link->project_id = $input['project_id'];
            $link->user_id = $user->id;
            $link->source_id = $sourceId;
            $link->target_id = $project->target_id;
            $link->source_entity = $input['source'];
            $link->target_entity = $input['target'];
            $link->up_votes = 1;
            $link->down_votes = 0;
            $link->score = 1;
            $link->status_id = 0;
            $link->link_type = $input['link_type'];
            $link->save();
            return 1;
        } else {
            return 0;
        }
    }


    public function import()
    {
        $import = Import::create(request()->all());
        $result = $this->import_links($import);
        if ($import->imported) {
            return \Illuminate\Support\Facades\Redirect::back()->with('notification', 'Links Imported!!!');
        } else {
            return \Illuminate\Support\Facades\Redirect::back()->with('error', 'An error Occured. Could not import Links!!!' . $result);
        }

    }

    public function convert(Import $import)
    {
        $command = 'rapper -i ' . $import->filetype . ' -o rdfxml-abbrev ' . $import->resource->path() . ' > ' . $import->resource->path() . '.rdf';
        $out = [];
        logger($command);
        exec($command, $out);
        logger(var_dump($out));
        return;
    }

    public function import_links(Import $import)
    {
        $graph = new Graph;
        try {
            if ($import->filetype != 'rdfxml') {
                $this->convert($import);
                $graph->parseFile($import->resource->path() . '.rdf', 'rdfxml');
            } else {
                $graph->parseFile($import->resource->path(), 'rdfxml');
            }
            $import->parsed = true;
            $import->save();
        } catch (Exception $ex) {
            $import->parsed = false;
            $import->save();
            return "Fail to parse file. Check filetype or valid syntax. Error:" . $ex;
        }
        $resources = $graph->resources();
        foreach ($resources as $resource) {
            $properties = $resource->propertyUris();
            foreach ($properties as $property) {
                $links = $resource->allResources(new EasyRdf_Resource((string) $property));
                foreach ($links as $link) {
                    $data = [
                        "source" => $resource->getUri(),
                        "target" => $link->getUri(),
                        "link_type" => $property,
                        "project_id" => $import->project_id,
                    ];
                    $request = \Illuminate\Support\Facades\Request::create("/", "GET", $data);
                    $content = new Content;
                    echo $this->create($content);
                }
            }
        }
        $import->imported = true;
        $import->save();
    }

    public function destroy($id)
    {
        $link = Link::findOrFail($id);
        try {
            // $this->authorize('destroy', $link);
            $link->delete();
            $data = [
                "priority" => "success",
                "title" => "Success",
                "message" => "Link Deleted!!!"
            ];
            return response()->json($data);
        } catch (Exception $ex) {
            $data = [
                "priority" => "error",
                "title" => "Error",
                "message" => "You are not authorized to delete this link!"
            ];
            return response()->json($data);
        }


    }

    public function delete_all(Request $request)
    {

        $project = Project::find($request->project_id);
        //dd($project);
        $links = $project->links;
        foreach ($links as $link) {
            // $this->authorize('destroy', $link);
            $link->delete();
        }
        return \Illuminate\Support\Facades\Redirect::back()->with('notification', 'All Links Deleted!!!');
    }

    public function CreateRDFGraph(Authenticatable $user, $project_id)
    {
        $myGraph = new Graph;
        $project = Project::find($project_id);
        $user = Auth::guard('admin')->user();

        if ($project == null) {
            foreach ($user->projects as $project) {
                $links = $project->links;
                foreach ($links as $link) {
                    $myGraph->addResource($link->source_entity, $link->link_type, $link->target_entity);
                }
            }
        } else {
            $links = $project->links;
            foreach ($links as $link) {
                $myGraph->addResource($link->source_entity, $link->link_type, $link->target_entity);
            }
        }
        return $myGraph;
    }

    public function CreateRDFGraph2($links)
    {
        $myGraph = new Graph;
        foreach ($links as $link) {
            $myGraph->addResource($link->source_entity, $link->link_type, $link->target_entity);
        }
        return $myGraph;
    }

    public function export(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $project_id = $request->project_id;
        if (!is_numeric($project_id) && !empty($project_id)) {
            $project = Project::where('name', '=', $project_id)->first();
            $project_id = $project->id;
        }
        $myGraph = LinkController::CreateRDFGraph($user, $project_id);
        $format = $request->format;
        LinkController::CreateRDFFile($myGraph, $format, $project_id);
    }

    public function export_voted(Request $request)
    {
        $project_id = $request->project_id;
        $links = Link::where("project_id", "=", $request->project_id)
            ->when(isset($request->score), function ($query) use ($request) {
                return $query->where('score', '>', $request->score);
            })
            ->get();
        $links = $links->filter(function ($link) {
            return $this->confidence($link) >= request("threshold") / 100;
        });
        $myGraph = $this->CreateRDFGraph2($links);
        $format = $request->filetype;
        $this->CreateRDFFile($myGraph, $format, $project_id);
    }

    public function confidence(Link $link)
    {
        $upVotes = $link->up_votes;
        $downVotes = $link->down_votes;
        $totalVotes = $upVotes + $downVotes;
        if ($totalVotes > 0) {
            return (double) $upVotes / $totalVotes;
        } else {
            return 0;
        }
    }

    function DownloadFile($file, $name, $format)
    { // $file = include path
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header('Content-Type: ' . $format);
            ob_clean();
            flush();
            readfile($file);
            unlink($file);
            exit;
        }
    }

    public function CreateRDFFile($myGraph, $format, $project_id)
    {
        // Ελέγξτε αν το format είναι υποστηριζόμενο
        $availableFormats = ['rdfxml', 'ntriples', 'turtle', 'json', 'csv'];

        if (!in_array($format, $availableFormats)) {
            throw new Exception("Format is not recognised: $format");
        }

        logger("Graph data: " . print_r($myGraph->dump(), true)); // Προσθήκη logging

        if ($format === 'csv') {
            $export = $this->createCsvExport($myGraph);
        } else {
            $export = $myGraph->serialise($format);
        }

        logger("Export data: " . substr($export, 0, 500)); // Προσθήκη logging

        $project = Project::find($project_id);
        $File_Ext = ($format === 'csv') ? 'csv' : Format::getFormat($format)->getDefaultExtension(); // get file extension
        $dt = Carbon::now();
        $time = Str::slug($dt->format("Y m d His"));

        if ($project_id == null) {
            $File_Name = "Export" . $time . "." . $File_Ext;
            $NewFileName = storage_path() . "/app/projects/" . $File_Name;
            file_put_contents($NewFileName, $export);
        } else {
            $File_Name = "Alignment_Export_" . Str::slug($project->name) . "_" . $time . "." . $File_Ext;
            $NewFileName = storage_path() . "/app/projects/project" . $project_id . "/" . $File_Name;
            file_put_contents($NewFileName, $export);
        }

        logger("File created: " . $NewFileName); // Προσθήκη logging

        $this->DownLoadFile($NewFileName, $File_Name, $format);
    }


    private function createCsvExport($myGraph)
    {
        $csv = '';

        // Προσθήκη των επικεφαλίδων στη CSV
        $headers = ['Subject', 'Predicate', 'Object'];
        $csv .= implode(',', $headers) . "\n";

        // Προσθήκη των δεδομένων RDF στη CSV
        foreach ($myGraph->resources() as $resourceUri => $resource) {
            logger("Resource URI: " . $resourceUri); // Προσθήκη logging
            foreach ($resource->propertyUris() as $propertyUri) {
                logger("Property URI: " . $propertyUri); // Προσθήκη logging
                foreach ($resource->all($propertyUri) as $object) {
                    $objectValue = $object->isResource() ? $object->getUri() : (string) $object;
                    logger("Object: " . $objectValue); // Προσθήκη logging
                    $row = [
                        (string) $resourceUri,
                        (string) $propertyUri,
                        $objectValue
                    ];
                    logger("CSV Row: " . implode(',', $row)); // Προσθήκη logging για κάθε σειρά
                    $csv .= implode(',', $row) . "\n";
                }
            }
        }

        logger("Final CSV content: " . $csv); // Προσθήκη logging για το τελικό περιεχόμενο του CSV

        return $csv;
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ajax()
    {
        logger("ajaxxxxx");

        $prefixes = \App\Models\Prefix::all();
        $user = Auth::guard('admin')->user();

        foreach ($prefixes as $prefix) {
            RdfNamespace::set($prefix->prefix, $prefix->namespace);
        }

        $project = Project::find(request()->project);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        if (request()->route == "mylinks") {
            $links = Link::where("project_id", "=", $project->id)
                ->where("user_id", "=", $user->id)
                ->orderBy("created_at", "desc")->get();
        } else {
            $links = Link::where("project_id", "=", $project->id)->orderBy("created_at", "desc")->get();
        }

        $file = new FileController();
        $source_graph = Cache::get($project->source_id . '_graph') ?: $file->cacheGraph(\App\Models\File::find($project->source_id));
        $target_graph = Cache::get($project->target_id . '_graph') ?: $file->cacheGraph(\App\Models\File::find($project->target_id));
        $ontologies_graph = Cache::get('ontologies_graph');

        return Datatables::of($links)
            ->addColumn('source', function ($link) use ($source_graph) {
                return view("links.resource", [
                    "resource" => $link->source_entity,
                    "graph" => $source_graph,
                ]);
            })
            ->addColumn('target', function ($link) use ($target_graph) {
                return view("links.resource", [
                    "resource" => $link->target_entity,
                    "graph" => $target_graph,
                ]);
            })
            ->addColumn('link', function ($link) use ($ontologies_graph) {
                return view("links.resource", [
                    "resource" => $link->link_type,
                    "graph" => $ontologies_graph,
                ]);
            })
            ->addColumn('action', function ($link) {
                $currentUser = Auth::guard('admin')->user();
                $projectOwner = $link->project->user;

                if ($link->user_id == $currentUser->id || $projectOwner->id == $currentUser->id) {
                    $class = "btn";
                } else {
                    $class = "btn disabled";
                }

                return '<button onclick="delete_link(' . $link->id . ')" class="' . $class . '" title="Delete this Link" style="background-color: red; font-weight: bold; font-size: 10px; padding: 8px;"><span style="color: white;">X</span></button>';
            })
            ->addColumn('project', function ($link) {
                return $link->project->name;
            })
            ->make(true);
    }

}