<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Admin\Controllers\FileController;
use EasyRdf\RdfNamespace;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['source_id', 'target_id', 'source_entity', 'target_entity', 'user_id'];
    protected $dates = ['deleted_at'];

    public function project()
    {
        return $this->belongsTo("App\Models\Project");
    }

    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }

    public function source()
    {
        return $this->hasOne('App\Models\File', 'id', 'source_id');
    }


    public function target()
    {
        return $this->hasOne('App\Models\File', 'id', 'target_id');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\Vote');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function humanize()
    {
        logger("humanize");
        $project = \App\Models\Project::find($this->attributes["project_id"]);
        $file = new FileController();
        $file->cacheGraph(\App\Models\File::find($project->source_id));
        $file->cacheGraph(\App\Models\File::find($project->target_id));
        $source_graph = \Illuminate\Support\Facades\Cache::get($project->source_id . "_graph");
        $target_graph = \Illuminate\Support\Facades\Cache::get($project->target_id . "_graph");
        $ontologies_graph = \Illuminate\Support\Facades\Cache::get('ontologies_graph');
        $source_label = \App\Models\RDFTrait::label($source_graph, $this->source_entity) ?: RdfNamespace::shorten($this->source_entity, true);
        $this->source_label = $source_label;
        $target_label = \App\Models\RDFTrait::label($target_graph, $this->target_entity) ?: RdfNamespace::shorten($this->target_entity, true);
        $this->target_label = $target_label;
        $link_label = \App\Models\RDFTrait::label($ontologies_graph, $this->link_type) ?: RdfNamespace::shorten($this->link_type, true);
        $this->link_label = $link_label;
        // $vote = $this->myVote();
        // $this->myvote = $vote != null ? $vote->vote : null;
        return $this;
    }
}
