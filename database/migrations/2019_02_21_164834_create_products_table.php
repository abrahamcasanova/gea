<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clabe')->nullable();;
            $table->string('name')->nullable();;
            $table->text('url_image')->nullable();;
            $table->text('description')->nullable();;
            $table->string('location')->nullable();;
            $table->unsignedInteger('product_type_id')->nullable();
            $table->char('status',1)->default(1);
            $table->timestamps();

            $table->foreign('product_type_id')->references('id')->on('product_types')
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
        Schema::dropIfExists('products');
    }
}
