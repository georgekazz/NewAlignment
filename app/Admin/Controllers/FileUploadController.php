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

                // Αποθηκεύστε το αρχείο στον τοπικό δίσκο (storage/app/uploads)
                $path = $file->move(storage_path('app/uploads'), $filename);

                if ($path) {
                    // Δημιουργία αντικειμένου File στη βάση δεδομένων
                    $fileData = [
                        'filename' => $filename,
                        'resource' => $path,
                        'filetype' => 'ttl',
                        'public' => 1,
                        'parsed' => 0,
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
}
