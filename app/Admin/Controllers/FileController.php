<?php

namespace App\Admin\Controllers;

use App\Models\File;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Facades\Admin;
use App\Jobs\Parse;


class FileController extends AdminController
{

    protected $title = 'My ontologies';

    public function grid()
    {
        $user = auth()->user();
        $id = request('file');

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
                'filename' => $file->getClientOriginalName(),
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
        // $this->authorize('destroy', $file);
        $file->delete();

        admin_toastr('File has been successfully deleted!', 'info', ['duration' => 5000]);

        return redirect(admin_url('mygraphs'));
    }

    public function parse(File $file)
    {
        $currentUser = Admin::user();

        Parse::dispatch($file, $currentUser)->onQueue('parse_jobs');

        return redirect()->back();
    }
}
