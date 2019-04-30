<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDeleteAtToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('customer_orders', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
