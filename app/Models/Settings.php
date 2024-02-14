<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'public',
        'valid',
        'resource',
        'suggestion_provider_id'
    ];

    public function projects()
    {
        return $this->belongsToMany("App\Project", 'projects', 'settings_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo("\App\Models\SuggestionProvider", "suggestion_provider_id");
    }
    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }
}
