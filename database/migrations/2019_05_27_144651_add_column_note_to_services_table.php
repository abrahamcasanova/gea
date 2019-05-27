<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNoteToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('frecuency', 'frequency');
            $table->integer('date_payment')->change();
            $table->softDeletes();
            $table->string('note')->nullable()->after('frecuency');
            $table->char('status',1)->default(1)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('frequency', 'frecuency');
            $table->date('date_payment')->change();
            $table->dropColumn(['note','status']);
            $table->dropSoftDeletes();
        });
    }
}
