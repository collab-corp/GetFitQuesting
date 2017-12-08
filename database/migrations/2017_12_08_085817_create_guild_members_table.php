<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuildMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('guild_id')->index()->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('guild_id')->references('id')->on('guilds');
            $table->string('description')->nullable();
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
        Schema::drop('guild_members');
    }
}
