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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->unsignedBigInteger('employees_id');
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->decimal('total_income', 15, 2);
            $table->decimal('total_deduction', 15, 2);
            $table->decimal('total_savings', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employees_id')->references('employees_id')->on('employees')->onDelete('cascade');
            $table->foreign('attendance_id')->references('attendance_id')->on('attendances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
