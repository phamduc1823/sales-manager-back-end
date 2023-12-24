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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->bigInteger('location_id')->nullable()->change();
            $table->string('username')->nullable()->change();
            $table->string('tel')->nullable()->change();
            $table->boolean('status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('location_id')->nullable(false)->change();
            $table->string('username')->nullable(false)->change();
            $table->string('tel')->nullable(false)->change();
            $table->tinyInteger('status')->change();
        });
    }
};
