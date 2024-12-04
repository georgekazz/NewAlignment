<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(\ProjectFilePivotTableSeeder::class);
         $this->call(\SettingsSeeder::class);
         $this->call(\LinkTypeSeeder::class);
         $this->call(\LabelExtractorSeeder::class);
         $this->call(\SuggestionProvidersSeeder::class);
    }
}
