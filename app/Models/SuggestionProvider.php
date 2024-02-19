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
        $configurationClass = $this->configuration;
        $configuration = new $configurationClass();
        $settings->valid = json_decode($configuration->validateSettingsFile($settings)->bag)->valid;
        $settings->save();
    }

    public function prepare(Project $project)
    {
        $configurationClass = $this->configuration;
        $configuration = new $configurationClass();
        $configuration->prepareProject($project);
    }
}
