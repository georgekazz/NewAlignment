<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinkTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->after('id')->nullable();
            $table->text('group')->after('user_id')->nullable();
            $table->text('inner')->after('group')->nullable();
            $table->text('value')->after('inner')->nullable();
            $table->boolean('public')->after('value')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link_types');
    }
}
