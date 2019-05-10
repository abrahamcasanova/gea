<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount')->nullable();
            $table->date('date_confirmation')->nullable();
            $table->string('type_of_voucher')->nullable();
            $table->unsignedInteger('type_of_payment_id')->nullable();
            $table->string('number_voucher')->nullable();
            $table->string('note')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('product_detail_sale_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_of_payment_id')->references('id')->on('type_of_payments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_detail_sale_id')->references('id')->on('product_detail_sales')
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
        Schema::dropIfExists('supplier_payments');
    }
}
