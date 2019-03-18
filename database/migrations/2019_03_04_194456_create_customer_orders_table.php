<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('travel_month')->nullable();
            $table->date('travel_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('call_time')->nullable();
            $table->string('with_us')->nullable();
            $table->string('travel_destination')->nullable();
            $table->integer('number_adults')->nullable();
            $table->string('number_childs')->nullable();
            $table->string('services')->nullable();
            $table->string('comments')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->char('status',1)->default(1);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')
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
        Schema::dropIfExists('customer_orders');
    }
}
