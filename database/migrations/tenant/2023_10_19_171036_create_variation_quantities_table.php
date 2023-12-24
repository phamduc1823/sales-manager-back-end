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
        Schema::create('variation_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->index('variation_id');
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->double('price_import');
            $table->integer('quantity');
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
        Schema::dropIfExists('variation_quantities');
    }
};
