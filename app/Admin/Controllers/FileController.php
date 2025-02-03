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
                'file' => 'required|max:2048', // Χωρίς το mimes
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
            $supportedFiletypes = ['ntriples', 'rdfxml', 'turtle', 'rdf'];
            if (!in_array($file->filetype, $supportedFiletypes)) {
                throw new \Exception("Unsupported filetype: " . $file->filetype);
            }
    
            // Logging αρχείου που προσπαθούμε να αναλύσουμε
            Log::debug("Parsing file ID: {$file->id}, Path: {$filePath}, Type: {$file->filetype}");
    
            // Ανάλυση αρχείου ή μετατροπή σε ntriples
            if ($file->filetype !== 'ntriples') {
                Log::debug("Converting file ID: {$file->id} to NTriples.");
                $convertedFilePath = $this->convert($file);
    
                if (!file_exists($convertedFilePath)) {
                    throw new \Exception("Converted file not found at: " . $convertedFilePath);
                }
    
                Log::debug("Successfully converted file ID: {$file->id}. Converted Path: {$convertedFilePath}");
    
                $graph->parseFile($convertedFilePath, 'ntriples');
            } else {
                Log::debug("Parsing NTriples file ID: {$file->id}");
                $graph->parseFile($filePath, 'ntriples');
            }
    
            // Ενημέρωση κατάστασης αρχείου
            $file->parsed = true;
            if (!$file->save()) {
                Log::warning("Failed to update the parsed status for file ID: {$file->id}");
            }
    
            // Ενημέρωση χρήστη
            $message = $file->filetype !== 'ntriples' ? 'Graph Converted and Parsed' : 'Graph Parsed';
            admin_toastr($message, 'success', ['duration' => 5000]);
    
            return redirect(admin_url('mygraphs'));
    
        } catch (\Exception $ex) {
            // Logging του σφάλματος
            Log::error("Error parsing file ID: {$file->id}. Exception: " . $ex->getMessage());
    
            // Ενημέρωση κατάστασης σε περίπτωση σφάλματος
            $file->parsed = false;
            if (!$file->save()) {
                Log::warning("Failed to update the parsed status for file ID: {$file->id} after error.");
            }
    
            // Ενημέρωση χρήστη για το σφάλμα
            admin_toastr('Failed to parse the graph. Please check the logs.', 'error', ['duration' => 5000]);
            return redirect(admin_url('mygraphs'));
        }
    }


    public function convert(File $file)
    {
        $filePath = 'file:///' . storage_path('app/' . $file->resource);
        $storagePath = storage_path('app/' . $file->resource);

        if (!file_exists($storagePath)) {
            throw new \Exception("File not found at: " . $storagePath);
        }

        logger('Processing file: ' . $filePath);

        $extension = pathinfo($file->resource, PATHINFO_EXTENSION);
        
        if ($extension === 'rdf') {
            $parser = \ARC2::getRDFParser();
        } elseif ($extension === 'ttl') {
            $parser = \ARC2::getTurtleParser();
        } else {
            throw new \Exception("Unsupported file format: " . $extension);
        }

        // Εκτέλεση του κατάλληλου parser
        $parser->parse($filePath);

        if (count($parser->errors) > 0) {
            logger('Parsing errors: ' . implode(", ", $parser->errors));
            throw new \Exception('Error parsing file: ' . implode(", ", $parser->errors));
        }

        $triples = $parser->getTriples();
        if (!is_array($triples) || empty($triples)) {
            throw new \Exception("No triples found in the file or invalid format.");
        }

        $serializer = \ARC2::getNTriplesSerializer();
        $doc = $serializer->getSerializedTriples($triples);

        $ntFileName = pathinfo($file->resource, PATHINFO_FILENAME) . '.nt';
        $ntFilePath = dirname($storagePath) . '/' . $ntFileName;

        if (file_put_contents($ntFilePath, $doc) === false) {
            throw new \Exception("Failed to write NTriples file at: " . $ntFilePath);
        }

        return $ntFilePath;
    }


}
