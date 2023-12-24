<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table ->string('name');
            $table ->string('image')->nullable();
            $table ->text('description')->nullable();
            $table ->string('tel')->nullable();
            $table ->string('email');
            $table ->unsignedBigInteger('province_code');
            $table ->unsignedBigInteger('district_code');
            $table ->unsignedBigInteger('ward_code');
            $table ->string('address_detail');
            $table ->tinyInteger('status')->default(1);
            $table ->tinyInteger('is_main')->default(0);
            $table ->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('locations');
    }
};
