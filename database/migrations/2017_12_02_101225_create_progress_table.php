<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quest_id')->nullable();
            $table->foreign('quest_id')->references('id')->on('quests');
            $table->unsignedInteger('story_id')->nullable();
            $table->foreign('story_id')->references('id')->on('stories');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->integer('experience')->default(0);
            $table->timestamps();

            $table->index(['quest_id', 'story_id', 'user_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('progress');
    }
}
