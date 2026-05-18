<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jadikan supplier_id nullable di tabel spareparts
     * agar suku cadang bisa disimpan tanpa supplier.
     */
    public function up(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            // Drop foreign key dulu sebelum ubah kolom
            $table->dropForeign(['supplier_id']);
            $table->unsignedBigInteger('supplier_id')->nullable()->change();
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->unsignedBigInteger('supplier_id')->nullable(false)->change();
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('cascade');
        });
    }
};
