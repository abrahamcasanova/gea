<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quote_id');
            $table->unsignedInteger('user_id');
            $table->string('track_status',40);
            $table->date('contact_date');
            $table->longText('comments');
            $table->char('status',1)->default(1);
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('quote_tracks');
    }
}
