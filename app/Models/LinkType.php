<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkType extends Model
{
    use HasFactory;
    protected $fillable = [ 'user_id', 'group', 'value', 'inner', 'public'];
    
    public function user(){
        return $this->belongsTo("App\Models\User");
    }
}
