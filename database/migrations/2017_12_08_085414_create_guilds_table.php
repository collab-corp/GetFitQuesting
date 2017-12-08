<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guilds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('creator_id')->index();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedInteger('members_count')->default(0);
            $table->string('name');
            $table->text('description');
            $table->string('banner')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guilds');
    }
}
