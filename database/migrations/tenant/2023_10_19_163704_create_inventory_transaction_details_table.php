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
        Schema::create('inventory_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transaction_id');
            $table->index('inventory_transaction_id');
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->double('price');
            $table->tinyInteger('price_type');
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
        Schema::dropIfExists('inventory_transaction_details');
    }
};
