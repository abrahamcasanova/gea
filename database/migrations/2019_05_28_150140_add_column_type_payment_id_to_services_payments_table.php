<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypePaymentIdToServicesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services_payments', function (Blueprint $table) {
            $table->unsignedInteger('type_of_payment_id')->nullable()->after('status');

            $table->foreign('type_of_payment_id')->references('id')->on('type_of_payments')
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
        Schema::table('services_payments', function (Blueprint $table) {
            $table->dropForeign(['type_of_payment_id']);
            $table->dropColumn(['type_of_payment_id']);
        });
    }
}
