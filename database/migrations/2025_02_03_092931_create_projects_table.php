<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('public')->default(false);
            $table->foreignId('source_id')->constrained('sources')->onDelete('cascade');
            $table->foreignId('target_id')->constrained('targets')->onDelete('cascade');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->string('name');
            $table->foreignId('settings_id')->constrained('settings')->onDelete('cascade');
            $table->boolean('processed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
