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
        Schema::create('car_types', function (Blueprint $table) {
            $table->id('car_type_id');
            $table->unsignedBigInteger('engine_type_id')->nullable();
            $table->string('chassis_number', 255);
            $table->string('name', 255);
            $table->string('series', 255);
            $table->string('engine_code', 255);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();

            $table->foreign('engine_type_id')->references('engine_type_id')->on('engine_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_types');
    }
};
