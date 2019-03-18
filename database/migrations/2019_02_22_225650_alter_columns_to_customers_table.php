<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name',90)->nullable()->change();
            $table->string('last_name',90)->nullable()->change();
            $table->date('birthdate')->nullable()->after('last_name');
            $table->string('level')->nullable()->after('birthdate');
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('email')->nullable()->change();
            $table->string('type_of_person')->nullable()->change()->comment('1 = PROSPECTO, 2 = CLIENTE');
            $table->text('comment')->nullable()->after('type_of_person');
            $table->string('rfc',13)->nullable()->after('email');
            $table->string('phone',90)->nullable()->after('rfc');
            $table->string('cellphone',90)->nullable()->after('phone');

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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->string('first_name',90)->nullable()->change();
            $table->string('last_name',90)->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('type_of_person')->nullable()->comment('1 = PROSPECTO, 2 = CLIENTE')->change();
            $table->dropColumn(['comment','rfc','phone','cellphone','birthdate','level','customer_id']);
        });
    }
}
