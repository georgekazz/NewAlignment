<?php

namespace App\Jobs;


use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Project;
use Illuminate\Foundation\Bus\Dispatchable;
class Convert
{
    use Dispatchable;
    use InteractsWithQueue, SerializesModels;

    protected $project,$user, $dump;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project, $user, $dump)
    {
        $this->project = $project;
        $this->user = $user;
        $this->dump = $dump;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        admin_toastr('Converting Graphs...', 'info', ['duration' => 5000]);
        logger("Convering Graphs...", ['dump' => $this->dump]);


         $controller = new \App\Admin\Controllers\CreatelinksController();
         $controller->D3_convert($this->project, $this->dump);

        if($this->dump === "target"){

            admin_toastr('Project Ready!', 'success', ['duration' => 5000]);
            logger("Project Ready!");
            $this->project->processed = 1;
            $this->project->save();
        }

    }
}
