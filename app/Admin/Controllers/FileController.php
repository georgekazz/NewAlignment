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
                'status' => 0,
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
            // Έλεγχος υποστηριζόμενων τύπων αρχείου
            $supportedFiletypes = ['ntriples', 'rdfxml', 'turtle','rdf'];
            if (!in_array($file->filetype, $supportedFiletypes)) {
                throw new \Exception("Unsupported filetype: " . $file->filetype);
            }

            // Ανάλυση αρχείου ή μετατροπή σε ntriples
            if ($file->filetype != 'ntriples') {
                $convertedFilePath = $this->convert($file);
                $graph->parseFile($convertedFilePath, 'ntriples');
            } else {
                $graph->parseFile($filePath, 'ntriples');
            }

            // Ενημέρωση κατάστασης αρχείου
            $file->parsed = true;
            if (!$file->save()) {
                Log::warning("Failed to update the parsed status for file ID: {$file->id}");
            }

            // Ενημέρωση χρήστη
            $message = $file->filetype != 'ntriples' ? 'Graph Converted and Parsed' : 'Graph Parsed';
            admin_toastr($message, 'success', ['duration' => 5000]);

            return redirect(admin_url('mygraphs'));

        } catch (\Exception $ex) {
            // Διαχείριση εξαίρεσης
            $file->parsed = false;
            if (!$file->save()) {
                Log::warning("Failed to update the parsed status for file ID: {$file->id} after error.");
            }
            Log::error("Error parsing file ID: {$file->id}. Exception: " . $ex->getMessage());
            admin_toastr('Failed to parse the graph. Please check the logs.', 'error', ['duration' => 5000]);
            return redirect(admin_url('mygraphs'));
        }
    }


    public function convert(File $file)
    {
        // Απόλυτο μονοπάτι του αρχείου
        $filePath = 'file:///' . storage_path('app/' . $file->resource);

        if (!file_exists(storage_path('app/' . $file->resource))) {
            throw new \Exception("File not found at: " . storage_path('app/' . $file->resource));
        }

        logger('File path: ' . $filePath);

        // Δημιουργούμε έναν RDF/XML παράγοντα
        $rdfXmlParser = \ARC2::getRDFXMLParser();
        $rdfXmlParser->parse($filePath);

        // Έλεγχος σφαλμάτων ανάλυσης RDF/XML
        if (count($rdfXmlParser->errors) > 0) {
            logger('RDF/XML parsing errors: ' . implode(", ", $rdfXmlParser->errors));
            return 'Error parsing RDF/XML: ' . implode(", ", $rdfXmlParser->errors);
        }

        // Λήψη τριπλών
        $triples = $rdfXmlParser->getTriples();
        if (empty($triples)) {
            throw new \Exception("No triples found in the RDF/XML file.");
        }

        // Σειριοποίηση σε NTriples
        $ser = \ARC2::getNTriplesSerializer();
        $doc = $ser->getSerializedTriples($triples);

        // Δημιουργούμε το αρχείο NTriples
        $ntFileName = pathinfo($file->resource, PATHINFO_FILENAME) . '.nt';
        $path = Storage::path($file->resource);
        $ntFilePath = dirname($path) . '/' . $ntFileName;

        if (file_put_contents($ntFilePath, $doc) === false) {
            throw new \Exception("Failed to write NTriples file at: " . $ntFilePath);
        }

        // Επιστρέφουμε το απόλυτο μονοπάτι του αρχείου NTriples
        return $ntFilePath;
    }

}
