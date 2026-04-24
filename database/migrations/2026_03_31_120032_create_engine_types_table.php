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
        Schema::create('engine_types', function (Blueprint $table) {
            $table->id('engine_type_id');
            $table->string('name', 255);
            $table->string('cylinders', 255);
            $table->decimal('oil_cap', 5, 2);
            $table->enum('fuel_type', ['Bensin', 'Diesel']);
            $table->integer('engine_cap');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engine_types');
    }
};
