<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWithAuthorizationToTypeOfPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('type_of_payments', function (Blueprint $table) {
            $table->boolean('with_authorization')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_of_payments', function (Blueprint $table) {
            $table->dropColumn(['with_authorization']);
        });
    }
}
