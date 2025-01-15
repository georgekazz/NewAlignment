<?php

namespace App\Models\SuggestionConfigurations;

use App\Models\Project;
use App\Models\Settings;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Storage;
use Cache;
use EasyRdf\Graph;

class SilkConfiguration
{
    public $nodes = [
        "{}Prefixes",
        "{}DataSources",
        "{}Interlinks",
        "{}Outputs"
    ];

    public function prepareProject(Project $project)
    {
        Storage::disk("projects")->makeDirectory("project" . $project->id);

        $filePath = storage_path('app/' . $project->source->resource);

        if (str_ends_with($project->source->resource, '.rdf')) {
            $filePath = str_replace('.rdf', '', $filePath);
        }

        if (!file_exists($filePath . '.nt')) {
            logger("Το αρχείο source δεν βρέθηκε: " . $filePath . '.nt');
        }

        $source = file_get_contents($filePath . '.nt');
        Storage::disk("projects")->put("/project" . $project->id . "/source.nt", $source);

        $filePath2 = storage_path('app/' . $project->target->resource);

        if (str_ends_with($project->target->resource, '.rdf')) {
            $filePath2 = str_replace('.rdf', '', $filePath2);
        }

        if (!file_exists($filePath2 . '.nt')) {
            logger("Το αρχείο target δεν βρέθηκε: " . $filePath2 . '.nt');
        }

        $target = file_get_contents($filePath2 . '.nt');
        Storage::disk("projects")->put("/project" . $project->id . "/target.nt", $target);

        $newConfig = $this->reconstruct($project->settings->id);
        Storage::disk("projects")->put("/project" . $project->id . "/project" . $project->id . "_config.xml", $newConfig);

        return 0;
    }

    public function reconstruct($id)
    {
        $settings = Settings::find($id);
        $filePath = storage_path('app/uploads/project2_config.xml' . $settings->resource);
        $settings_xml = file_get_contents($filePath);
        $new = $this->parseXML($settings_xml);

        $prefixes = $this->getNode($new, $this->nodes[0]);
        $datasources = $this->getNode($new, $this->nodes[1]);
        $linkage = $this->getNode($new, $this->nodes[2]);
        $outputs = $this->getNode($new, $this->nodes[3]);

        $newOutput = $this->createOutput($outputs->first());
        $newDatasource = $this->createDatasource($datasources->first());

        $service = new \Sabre\Xml\Service();
        $xml = $service->write(
            'Silk',
            [
                $prefixes->first(),
                $newDatasource,
                $linkage->first(),
                $newOutput
            ]
        );
        return $xml;
    }

    public function getNode($collection, $name)
    {
        $node = collect($collection->where("name", $name));
        return $node;
    }

    public function parseXML($xml)
    {

        $service = new \Sabre\Xml\Service();
        $result = collect($service->parse($xml));
        return $result;
    }

    public function createOutput($originalOutput)
    {
        $minConcfidence = $originalOutput["value"][0]["attributes"]["minConfidence"];
        $newOutput = [
            "name" => "Outputs",
            "value" => [
                [
                    "name" => "Output",
                    "value" => [

                        [
                            "name" => "Param",
                            "value" => null,
                            "attributes" => [
                                "name" => "file",
                                "value" => "score.nt"
                            ]
                        ],
                        [
                            "name" => "Param",
                            "value" => null,
                            "attributes" => [
                                "name" => "format",
                                "value" => "N-Triples"
                            ]
                        ]
                    ],
                    "attributes" => [
                        "id" => "score",
                        "type" => "alignment",
                        "minConfidence" => $minConcfidence
                    ]
                ]
            ],
            "attributes" => []
        ];
        return $newOutput;
    }

    public function filenameTemplate($filename)
    {
        return [
            "name" => "{}Param",
            "value" => null,
            "attributes" => [
                "name" => "file",
                "value" => $filename
            ]
        ];
    }

    public function formatTemplate()
    {
        return [
            "name" => "{}Param",
            "value" => null,
            "attributes" => [
                "name" => "format",
                "value" => "N-Triples"
            ]
        ];
    }

    public function createDataset($dataset, $filename)
    {
        $name = $dataset["name"];
        $file = $this->filenameTemplate($filename);
        $format = $this->formatTemplate();
        $graph = isset($dataset["value"][2]) ? $dataset["value"][2] : null;
        $attributes = $dataset["attributes"];
        return [
            "name" => $name,
            "value" => [
                $file,
                $format,
                $graph
            ],
            "attributes" => $attributes
        ];
    }

    public function createDatasource($originalDataSource)
    {
        $source = $this->createDataset($originalDataSource["value"][0], "source.nt");
        $target = $this->createDataset($originalDataSource["value"][1], "target.nt");
        return [
            "name" => "DataSources",
            "value" => [
                $source,
                $target,
            ],
            "attributes" => []
        ];
    }

    public function validateSettingsFile(Settings $settings)
    {
        libxml_use_internal_errors(true);
        $filePath = storage_path('app/uploads/project2_config.xml' . $settings->resource);

        try {
            $contents = file_get_contents($filePath);
            $schema = $this->validateSchema($contents);
            dd($schema);
        } catch (\Exception $e) {
            // Αντιμετωπίστε το σφάλμα εδώ
            admin_toastr('An error occurred!', 'error', ['duration' => 5000]);
            return null;
        }
    }

    public function validateXML($xml)
    {
        return 1;
    }

    public function validateAlignment(Settings $settings)
    {
        $filePath = storage_path('app/uploads/project2_config.xml' . $settings->resource);
        $xml = file_get_contents($filePath);
        $parsed = $this->parseXML($xml);
        dd("helpppppp", $parsed);
        $linkage = $this->getNode($parsed, $this->nodes[2]);
        $source = $linkage[2]["value"][0]["value"][0]["attributes"]["dataSource"];
        if ($source != "source.nt") {
            return 0;
        }
    }

    public function validateSchema($file)
    {
        libxml_use_internal_errors(true);
        $xml = new \DOMDocument();
        $errors = [];
        if (!$xml->load($file)) {
            foreach (libxml_get_errors() as $error) {
                array_push($errors, $error);
            }
            libxml_clear_errors();
        }
        $schema = storage_path() . "/app/projects/LinkSpecificationLanguage.xsd";
        $bag = [
            "valid" => $xml->schemaValidate($schema),
            "errors" => $errors
        ];
        return collect($bag);
    }

    public function runSiLK(Project $project, $user_id)
    {
        $id = $project->id;
        $filename = storage_path() . "/app/projects/project" . $id . "/project" . $id . "_config.xml";
        logger('Started Job...');

        $process = new Process([
            'java',
            '-Xms2048M',
            '-Xmx4096M',
            '-DconfigFile=' . $filename,
            '-Dreload=true',
            '-Dthreads=4',
            '-jar',
            app_path() . '/functions/silk/silk.jar'
        ]);

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            logger("correct");
        } catch (ProcessFailedException $exception) {
            logger("false: " . $exception->getMessage());
        }
        //$settingsID = $project->settings->id;
        if (Storage::disk("projects")->exists("/project" . $project->id . "/score_project" . $project->id . ".nt")) {
            Storage::disk("projects")->delete("/project" . $project->id . "/score_project" . $project->id . ".nt");
        }
        //Storage::disk("projects")->move("/project" . $project->id . "/score.nt", "/project" . $project->id . "/score_project" . $project->id . ".nt");

        admin_toastr('Finished SiLK similarities Calculations...', 'info', ['duration' => 5000]);
        logger("Finished SiLK similarities Calculations...");
        dispatch(new \App\Jobs\ParseScores($project, $user_id));
        //$this->parseScore($project, $user_id);
        dispatch(new \App\Jobs\Convert($project, $user_id, "source"));
        dispatch(new \App\Jobs\Convert($project, $user_id, "target"));
    }

    public function parseScore(Project $project, $user_id)
    {
        $old_score = storage_path() . "/app/projects/project" . $project->id . "/score.nt";
        $score_filepath = storage_path() . "/app/projects/project" . $project->id . "/score_project" . $project->id . ".nt";

        try {
            // Parse RDF/XML file to EasyRdf Graph object
            $graph = new Graph();
            $graph->parseFile($old_score, 'rdfxml');

            // Serialize the graph to N-Triples format
            $ntriples = $graph->serialise('ntriples');

            // Write N-Triples to file
            file_put_contents($score_filepath, $ntriples);

            admin_toastr('Converted Score Graph...', 'success', ['duration' => 5000]);
            logger("Converted Score Graph...");
        } catch (\Exception $ex) {
            logger($ex);
        }

        try {
            // Parse N-Triples file to EasyRdf Graph object
            $scores = new Graph;
            $scores->parseFile($score_filepath, "ntriples");
            admin_toastr('Parsed and Stored Graphs!', 'success', ['duration' => 5000]);
            logger("Parsed and Stored Graphs!");

            // Cache the scores graph
            Cache::forever("scores_graph_project" . $project->id, $scores);
            logger()->info("Scores cached successfully for project: " . $project->id);
        } catch (\Exception $ex) {
            logger($ex);
        }

        logger("converting files");
    }
}
