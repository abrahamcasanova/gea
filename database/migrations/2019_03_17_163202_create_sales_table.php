<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price')->default(0);
            $table->date('date_payment_limit')->nullable();
            $table->date('date_payment_supplier')->nullable();
            $table->date('date_advance')->nullable();
            $table->date('date_reminder')->nullable();
            $table->string('schedule')->nullable();
            $table->float('amount_receivable')->nullable();
            $table->string('simple_room')->nullable();
            $table->string('double_room')->nullable();
            $table->string('triple_room')->nullable();
            $table->string('quadruple_room')->nullable();
            $table->float('rate_price')->nullable();
            $table->string('confirmation')->nullable();
            $table->string('path')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('quote_id')->nullable();
            $table->unsignedInteger('quote_detail_id')->nullable();
            $table->timestamps();

            $table->foreign('quote_detail_id')->references('id')->on('quote_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')
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
        Schema::dropIfExists('sales');
    }
}
