<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('proposal_date')->nullable();
            $table->string('travel_date')->nullable();
            $table->integer('number_adults')->nullable();
            $table->integer('number_childs')->nullable();
            $table->longText('include')->nullable();
            $table->longText('policy')->nullable();
            $table->longText('payment')->nullable();
            $table->longText('path')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('customer_order_id')->nullable();
            $table->timestamps();

            $table->foreign('customer_order_id')->references('id')->on('customer_orders')
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
        Schema::dropIfExists('quotes');
    }
}
