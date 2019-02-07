<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serie')->nullable();
            $table->date('initial_validity')->nullable();
            $table->date('final_validity')->nullable();
            $table->float('net_premium')->nullable();
            $table->float('policy_right')->nullable();
            $table->float('policy_surcharge')->nullable();
            $table->float('vat')->nullable();
            $table->float('total_premium')->nullable();
            $table->boolean('payment')->nullable()->default(false);
            $table->date('payment_date')->nullable();
            $table->text('note');
            $table->boolean('status')->default(1);
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
