<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\File;
use App\Models\User;
use Cache;

class Parse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;

    protected $user;
    /**
     * Create a new job instance.
     */
    public function __construct(File $file, \OpenAdmin\Admin\Auth\Database\Administrator $user)
    {
        $this->file = $file;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $administrator = \OpenAdmin\Admin\Facades\Admin::user();

        $user = new User;
        $user->id = $administrator->id;
        $user->name = $administrator->name;

        $this->invalidate();

        Rapper::withChain([
            new Skosify($this->file, $user),
            new CacheGraph($this->file, $user),
        ])->dispatch($this->file, $user);
    }

    public function invalidate()
    {
        $this->file->parsed = false;
        $this->file->save();
        if (Cache::has($this->file->id . '_graph')) {
            Cache::forget($this->file->id . '_graph');
        }
    }

}
