<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionProvider extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "configuration"];

    public function validate(Settings $settings)
    {
        $configuration = new $this->configuration();
        dd($configuration);
        $settings->valid = json_decode($configuration->validateSettingsFile($settings)->bag)->valid;
        $settings->save();
    }

    public function prepare(Project $project)
    {
        $configuration = new $this->configuration();
        $configuration->prepareProject($project);
    }
}
