<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuarryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarry_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quarry_id');
            $table->integer('product_id');
            $table->double('price', 10, 2);
            $table->double('ef', 10, 2)->comment('Extraction Fee');
            $table->double('rmf', 10, 2)->comment('Road Maintenance Fee');
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
        Schema::dropIfExists('quarry_products');
    }
}
