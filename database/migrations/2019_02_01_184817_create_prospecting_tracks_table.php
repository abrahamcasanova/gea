<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectingTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospecting_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('prospecting_id');
            $table->unsignedInteger('user_assistant_id')->nullable();
            $table->string('status_prospecting');
            $table->string('note');
            $table->char('status',1)->default(1);
            $table->timestamps();

            $table->foreign('prospecting_id')->references('id')->on('prospectings')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_assistant_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospecting_tracks');
    }
}
