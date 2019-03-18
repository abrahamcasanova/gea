<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category')->nullable();
            $table->float('price')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('quote_id')->nullable();
            $table->char('status',1)->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')
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
        Schema::dropIfExists('quote_details');
    }
}
