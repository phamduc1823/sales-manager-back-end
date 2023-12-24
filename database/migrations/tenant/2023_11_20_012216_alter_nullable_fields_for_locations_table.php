<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('province_code')->nullable()->change();
            $table->unsignedBigInteger('district_code')->nullable()->change();
            $table->unsignedBigInteger('ward_code')->nullable()->change();
            $table->unsignedBigInteger('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('province_code')->change();
            $table->unsignedBigInteger('district_code')->change();
            $table->unsignedBigInteger('ward_code')->change();
            $table->unsignedBigInteger('created_by')->change();
        });
    }
};
