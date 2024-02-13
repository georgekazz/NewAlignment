<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Http\Request;

class ProjectController extends AdminController
{
    protected $title = 'Projects';
    public function grid()
    {
        $projects = Project::all();
        return view('projects.projecttable', compact('projects'));
    }

    // public function show($id)
    // {
    //     $project = Project::findOrFail($id);
    //     return view('projects.show', compact('project'));
    // }
}
