<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoughtProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bought_products', function (Blueprint $table) {
            $table->id();
            $table->integer('log_id');
            $table->integer('product_id');
            $table->double('price', 10, 2);
            $table->integer('quantity');
            $table->double('ef', 10, 2);
            $table->double('rmf', 10, 2);
            $table->integer('capacity');
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
        Schema::dropIfExists('bought_products');
    }
}
