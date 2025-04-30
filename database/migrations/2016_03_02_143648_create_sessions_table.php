<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Laravel uses string session IDs
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload'); // This is where Laravel stores session data
            $table->integer('last_activity')->index();

            // Custom fields (if needed)
            $table->integer('source_id')->nullable();
            $table->integer('target_id')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->nullableTimestamps(); // created_at and updated_at (nullable)
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }
}
