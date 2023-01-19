<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuarriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email');
            $table->string('address');
            $table->string('contact_no');
            $table->string('lgu');
            $table->string('host_barangay');
            $table->string('quarry_area_td');
            $table->json('ie_route');
            $table->json('provincial_permit');
            $table->json('regional_permit');
            $table->string('password');
            $table->timestamps();
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
        Schema::dropIfExists('quarries');
    }
}
