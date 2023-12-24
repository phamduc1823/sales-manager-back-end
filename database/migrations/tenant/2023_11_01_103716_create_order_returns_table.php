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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->index('order_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('customer_id');
            $table->string('reason');
            $table->tinyInteger('swap');
            $table->tinyInteger('has_returned');
            $table->double('return_total_price');
            $table->tinyInteger('payment_by_type');
            $table->double('return_charge');
            $table->double('has_payed');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('order_returns');
    }
};
