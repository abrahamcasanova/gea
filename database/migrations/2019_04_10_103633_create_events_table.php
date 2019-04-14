<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->string('details')->nullable();
            $table->string('firebase_id')->nullable();
            $table->boolean('open')->default(0);
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('schedule')->nullable();
            $table->date('date_advance')->nullable();
            $table->date('date_payment_limit')->nullable();
            $table->date('date_payment_supplier')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('quote_id')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')
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
        Schema::dropIfExists('events');
    }
}
