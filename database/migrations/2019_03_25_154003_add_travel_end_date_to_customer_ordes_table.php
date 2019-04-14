<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTravelEndDateToCustomerOrdesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->date('travel_end_date')->nullable()->after('travel_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropColumn(['travel_end_date']);
        });
    }
}
