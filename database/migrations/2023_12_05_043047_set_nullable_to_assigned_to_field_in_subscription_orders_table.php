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
        Schema::table('subscription_orders', function (Blueprint $table) {
            //
            if (Schema::hasColumn('subscription_orders', 'assigned_to')) {
                $table->string('assigned_to')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_orders', function (Blueprint $table) {
            //
            if (Schema::hasColumn('subscription_orders', 'assigned_to')) {
                $table->string('assigned_to')->change();
            }
        });
    }
};
