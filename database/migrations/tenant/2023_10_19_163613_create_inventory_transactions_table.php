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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->index('inventory_id');
            $table->unsignedBigInteger('inventory_id_out')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->tinyInteger('partner_type')->nullable();
            $table->tinyInteger('trans_type');
            $table->unsignedBigInteger('inventory_transaction_id');
            $table->index('inventory_transaction_id');
            $table->string('reason');
            $table->text('note')->nullable();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('inventory_transactions');
    }
};
