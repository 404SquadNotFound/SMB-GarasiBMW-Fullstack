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
        Schema::create('service_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('invoice_number', 100)->unique();
            $table->enum('branch', ['AHMAD_YANI', 'PELAJAR_PEJUANG']);
            $table->integer('odometer');
            $table->enum('status_service', ['menunggu', 'pengecekan', 'dikerjakan', 'dibatalkan', 'selesai']);
            $table->enum('status_payment', ['unpaid', 'paid']);
            $table->decimal('total_parts', 15, 2)->default(0);
            $table->decimal('total_service', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('vehicles_id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_transactions');
    }
};
