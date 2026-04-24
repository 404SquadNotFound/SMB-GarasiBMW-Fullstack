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
        Schema::create('savings', function (Blueprint $table) {
            $table->id('savings_id');
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('employees_id');
            $table->string('name', 255);
            $table->enum('status', ['locked', 'available', 'withdrawn', 'forfeited']);
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->timestamps();

            $table->foreign('payroll_id')->references('payroll_id')->on('payrolls')->onDelete('cascade');
            $table->foreign('employees_id')->references('employees_id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
