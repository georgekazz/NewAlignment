<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->onDelete('cascade');
            $table->boolean('valid')->default(true);
            $table->boolean('public')->default(false);
            $table->timestamps();
            $table->string('resource_file_name')->nullable();
            $table->integer('resource_file_size')->nullable();
            $table->string('resource_content_type')->nullable();
            $table->timestamp('resource_updated_at')->nullable();
            $table->foreignId('suggestion_provider_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
