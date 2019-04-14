<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_detail_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price')->default(0);
            $table->float('rate_price')->default(0);
            $table->string('confirmation')->default(0);
            $table->date('date_payment_supplier')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('quote_id')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')
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
        Schema::dropIfExists('product_detail_sales');
    }
}
