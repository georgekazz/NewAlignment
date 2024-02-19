<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
class ProjectController extends AdminController
{
    protected $title = 'Projects';
    public function grid()
    {
        $projects = Project::all();
        return view('projects.projecttable', compact('projects'));
    }

    public function create(\OpenAdmin\Admin\Layout\Content $content)
    {

        $user = Auth::guard('admin')->user();
        $input = request()->all();
        $input['user_id'] = $user->id;
        $input['public'] = ($input['access_type'] == 'public') ? 1 : 0;

        $project = Project::create($input);

        $source = $project->source;

        $target = $project->target;

        $source->projects()->attach($project->id);
        $target->projects()->attach($project->id);

        admin_toastr('Project Created!', 'success', ['duration' => 5000]);

        return redirect(admin_url('myprojects'));
    }
}
