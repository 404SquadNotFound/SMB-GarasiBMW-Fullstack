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
        Schema::create('car_works', function (Blueprint $table) {
            $table->id('car_work_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('sparepart_id')->nullable();
            $table->string('customer_name', 255);
            $table->string('phone_number', 255);
            $table->string('address', 255);
            $table->string('car_name', 255);
            $table->string('license_plate', 50);
            $table->string('engine_code', 255);
            $table->integer('odometer');
            $table->string('sparepart_name', 255)->nullable();
            $table->text('sparepart_used_amount')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('vehicles_id')->on('vehicles')->onDelete('cascade');
            $table->foreign('sparepart_id')->references('sparepart_id')->on('spareparts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_works');
    }
};
