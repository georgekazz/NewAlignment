<?php

namespace App\Admin\Controllers;

use App\Models\File;
use OpenAdmin\Admin\Controllers\AdminController;
use EasyRdf\Graph;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends AdminController
{
    protected $title = 'My ontologies';

    public function grid()
    {
        $files = File::all();
        return view('files.filetable', compact('files'));
    }

    public function store()
    {
        try {
            request()->validate([
                'file' => 'required|mimes:rdf,xml|max:2048',
                'filetype' => 'required|in:rdf,turtle,ntriples,xml',
                'access_type' => 'required|in:public,private',
            ]);

            if (request()->hasFile('file')) {
                $file = request()->file('file');
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename);
                session(['uploaded_filename' => $filename]);
            }

            $fileData = [
                'filename' => $filename,
                'resource' => $path,
                'filetype' => request()->input('filetype'),
                'public' => request()->input('access_type') === 'public',
                'parsed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $uploadedFile = File::create($fileData);

            admin_toastr('File has been successfully uploaded!', 'success', ['duration' => 5000]);

            return redirect(admin_url('mygraphs'));

        } catch (\Exception $e) {
            admin_toastr('File upload failed. Please try again', 'error', ['duration' => 5000]);
            return redirect(admin_url('mygraphs'));
        }
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        admin_toastr('File has been successfully deleted!', 'success', ['duration' => 5000]);

        return redirect(admin_url('mygraphs'));
    }

    public function parse(File $file)
    {
        $graph = new Graph();
        $filePath = Storage::path($file->resource);

        try {
            if ($file->filetype != 'ntriples') {

                $convertedData = $this->convert($file);

            } else {
                $graph->parseFile($filePath, 'ntriples');
            }
            $file->parsed = true;
            $file->save();

            admin_toastr('Graph Parsed', 'success', ['duration' => 5000]);

            return redirect(admin_url('mygraphs'));

        } catch (\Exception $ex) {
            $file->parsed = false;
            $file->save();
            Log::error($ex);
            admin_toastr('Failed to parse the graph. Please check the logs.', 'error', ['duration' => 5000]);
            return redirect(admin_url('mygraphs'));
        }
    }

    public function convert(File $file)
    {
        // Απόλυτο μονοπάτι του αρχείου
        $filePath = 'file:///' . storage_path('app/' . $file->resource);
        logger('file path: ' . $filePath);

        // Δημιουργούμε έναν RDF/XML παράγοντα
        $rdfXmlParser = \ARC2::getRDFXMLParser();
        //dd($rdfXmlParser);
        logger(json_encode($rdfXmlParser));
        $rdfXmlParser->parse($filePath);

        // Φορτώνουμε τα δεδομένα από το αρχείο και ελέγχουμε εάν η ανάλυση είναι επιτυχής
        if (count($rdfXmlParser->errors) == 0) {
            $triples = $rdfXmlParser->getTriples();

            $ser = \ARC2::getNTriplesSerializer();
            /* Serialize a triples array */
            $doc = $ser->getSerializedTriples($triples);

            // Δημιουργούμε το αρχείο NTriples
            $ntFileName = pathinfo($file->resource, PATHINFO_FILENAME) . '.nt';


            $path = Storage::path($file->resource);
            $ntFilePath = dirname($path) . '/' . $ntFileName;
            file_put_contents($ntFilePath, $doc);

            // Επιστρέφουμε το απόλυτο μονοπάτι του αρχείου NTriples
            return $ntFilePath;
        
        } else {

            return 'Error parsing RDF/XML';

        }
    }
}
