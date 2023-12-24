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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string("business_name");
            $table->string("tel")->nullable();
            $table->string("email");
            $table->string("business_field_code")->nullable();
            $table->tinyInteger("business_type")->nullable();
            $table->string("business_registration")->nullable();
            $table->date("license_date")->nullable();
            $table->text("license_address")->nullable();
            $table->string("province_code")->nullable();
            $table->string("district_code")->nullable();
            $table->string("ward_code")->nullable();
            $table->text("address_detail")->nullable();
            $table->string("logo")->nullable();
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
        Schema::dropIfExists('configs');
    }
};
