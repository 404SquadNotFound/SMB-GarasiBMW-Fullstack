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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employees_id');
            $table->string('name', 255);
            $table->date('join_date');
            $table->date('birth_date');
            $table->string('address', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', ['pemilik_bengkel', 'finance', 'kepala_bengkel', 'kepala_admin', 'admin', 'karyawan']);
            $table->decimal('base_salary', 15, 2);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('employees_id')->on('employees')->onDelete('set null');
            $table->foreign('edited_by')->references('employees_id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
