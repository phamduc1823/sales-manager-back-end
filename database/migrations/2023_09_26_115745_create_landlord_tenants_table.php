<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('name');
            $table->string('domain')->nullable();
            $table->string('database')->unique();
            $table->foreignId('business_field_id');
            $table->foreignId('user_id')->index();
            $table->text('address');
            $table->foreignId('pricing_id')->index();
            $table->date('due_at');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
