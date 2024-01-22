<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;

class File extends Model
{
    use HasFactory;
    protected $appends = ['tooltip'];
    protected $fillable = ['resource', 'filetype', 'public', 'user_id'];


    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'file_project', 'file_id', 'project_id');
    }

    public function cacheGraph()
    {
        if (Cache::has($this->id.'_graph')) {
            $graph = Cache::get($this->id.'_graph');
        } else {
            $graph = new \EasyRdf_Graph;
            $graph->parseFile($this->filenameSkosify(), 'ntriples');
            $this->parsed = true;
            $this->save();
            Cache::forever($this->id.'_graph', $graph);
        }

        return $graph;
    }
}
