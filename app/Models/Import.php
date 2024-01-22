<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fillable = ['resource', 'filetype', 'project_id', 'user_id'];
    
    public function __construct(array $attributes = [])
    {
        $this->hasAttachedFile('resource', [
        ]);

        parent::__construct($attributes);
    }
    
    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }

    public function project()
    {
        return $this->belongsTo("App\Models\Project");
    }
}
