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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');
            $table->unsignedBigInteger('created_by');
            $table->double('discount')->nullable();
            $table->tinyInteger('discount_type')->nullable();
            $table->double('tax')->nullable();
            $table->double('service_charge')->nullable();
            $table->integer('total_product');
            $table->double('total_price');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('payment_status')->default(0);
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
        Schema::dropIfExists('orders');
    }
};
