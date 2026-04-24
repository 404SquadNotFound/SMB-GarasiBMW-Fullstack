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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->string('item_name', 255);
            $table->enum('item_type', ['Service', 'Parts']);
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->foreign('transaction_id')->references('transaction_id')->on('service_transactions')->onDelete('cascade');
            $table->foreign('spare_part_id')->references('sparepart_id')->on('spareparts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
