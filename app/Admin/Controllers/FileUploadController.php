<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileUploadController extends AdminController
{
    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('ttlFile') && $request->file('ttlFile')->isValid()) {

                $file = $request->file('ttlFile');
                $filename = $file->getClientOriginalName();

                $path = $file->move(storage_path('app/uploads'), $filename);

                if ($path) {

                    $fileData = [
                        'filename' => $filename,
                        'resource' => $path,
                        'filetype' => 'ttl',
                        'public' => 1,
                        'parsed' => 0,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $uploadedFile = File::create($fileData);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'File uploaded successfully!',
                        'path' => Storage::url($path)
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File upload failed.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid file.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'File upload failed. ' . $e->getMessage()
            ]);
        }
    }

    public function processFile(Request $request, $fileId)
    {
        try {
            $file = File::findOrFail($fileId);

            $namespace = $request->input('namespace');
            $predicate = $request->input('predicate');

            $path = storage_path('app/uploads/' . $file->filename);
            $outputPath = $path . '.json';

            $curlCommand = "curl -X POST -F 'file=@" . $path . "' -F 'namespace=" . $namespace . "' -F 'predicate=" . $predicate . "' http://192.168.7.103:5000/upload -o " . $outputPath;

            exec($curlCommand, $output, $returnVar);

            \Log::info('Curl command: ' . $curlCommand);
            \Log::info('Curl output: ' . implode("\n", $output));
            \Log::info('Curl return code: ' . $returnVar);

            if ($returnVar == 0) {
                $file->status = true; //prepei na balw pedio status ston file table
                $file->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'File processed successfully!',
                    'fileId' => $file->id,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File processing failed. Curl error: ' . implode("\n", $output),
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('File processing failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'File processing failed. ' . $e->getMessage(),
            ]);
        }
    }



}
