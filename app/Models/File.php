<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    use HasFactory;
    protected $appends = ['tooltip'];
    protected $fillable = ['resource', 'filename', 'filetype', 'public', 'parsed', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'file_project', 'file_id', 'project_id');
    }
}
