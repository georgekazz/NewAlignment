<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'source_id', 'target_id', 'name', 'settings_id', 'public'];

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

    public function links()
    {
        return $this->hasMany('App\Models\Link');
    }

    public function settings()
    {
        return $this->hasOne('App\Models\Settings', 'id', 'settings_id');
    }

}
