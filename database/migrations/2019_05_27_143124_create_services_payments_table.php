<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount')->nullable();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->char('status',1)->default(1);
            $table->unsignedInteger('service_id')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')
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
        Schema::dropIfExists('services_payments');
    }
}
