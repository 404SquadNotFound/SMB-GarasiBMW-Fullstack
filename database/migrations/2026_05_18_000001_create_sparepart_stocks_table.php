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
        Schema::create('sparepart_stocks', function (Blueprint $table) {
            $table->id('stock_id');
            $table->unsignedBigInteger('sparepart_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('cost_off_sell', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('quantity');
            $table->date('date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('sparepart_id')->references('sparepart_id')->on('spareparts')->onDelete('cascade');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_stocks');
    }
};
