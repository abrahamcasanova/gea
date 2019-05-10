<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateLiquidateToProductDetailSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_detail_sales', function (Blueprint $table) {
            $table->date('date_liquidate')->nullable()->after('sale_id');
            $table->char('liquidate',1)->default(0)->after('date_liquidate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_detail_sales', function (Blueprint $table) {
            $table->dropColumn(['date_liquidate','liquidate']);
        });
    }
}
