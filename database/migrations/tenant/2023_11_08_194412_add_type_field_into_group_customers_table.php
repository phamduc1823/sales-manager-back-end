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
        Schema::table('group_customers', function (Blueprint $table) {
            //
            $table->boolean('type')->default(0)->comment('0: Khách hàng, 1: Nhà cung cấp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_customers', function (Blueprint $table) {
            //
            $table->dropColumn('type');
        });
    }
};
