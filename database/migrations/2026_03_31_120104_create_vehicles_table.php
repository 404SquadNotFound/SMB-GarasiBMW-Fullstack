<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicles_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('car_type_id');
            $table->string('model', 255);
            $table->string('engine_code', 255);
            $table->string('production_code', 255);
            $table->string('license_plate', 50);
            $table->integer('odometer');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('car_type_id')->references('car_type_id')->on('car_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
