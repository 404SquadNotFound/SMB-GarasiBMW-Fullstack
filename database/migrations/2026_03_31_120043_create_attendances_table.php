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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->enum('status', ['Hadir', 'Cuti', 'Sakit', 'Terlambat']);
            $table->string('reason', 255)->nullable();
            $table->time('clock_in')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('gps', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('employees_id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
