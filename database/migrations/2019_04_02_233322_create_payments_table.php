<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price')->default(0);
            $table->string('type_of_payment')->nullable();
            $table->float('percentage')->default(0);
            $table->float('exchange_rate')->default(0);
            $table->float('real_price')->default(0);
            $table->float('real_price_without_percentage')->default(0);
            $table->string('authorization_number')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->longText('comments')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')
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
        Schema::dropIfExists('payments');
    }
}
