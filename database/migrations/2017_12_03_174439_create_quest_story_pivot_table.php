<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestStoryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_story', function (Blueprint $table) {
            $table->integer('quest_id')->unsigned()->index();
            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
            $table->integer('story_id')->unsigned()->index();
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();
            $table->primary(['quest_id', 'story_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quest_story');
    }
}
