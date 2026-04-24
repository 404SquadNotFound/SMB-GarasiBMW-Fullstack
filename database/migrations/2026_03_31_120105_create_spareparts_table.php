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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('sparepart_id');
            $table->unsignedBigInteger('item_category_id');
            $table->unsignedBigInteger('car_type_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->string('item_code', 50)->unique();
            $table->string('name', 255);
            $table->string('category', 255);
            $table->decimal('cost_off_sell', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('quantity');
            $table->date('date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('item_category_id')->references('category_id')->on('item_categories')->onDelete('cascade');
            $table->foreign('car_type_id')->references('car_type_id')->on('car_types')->onDelete('set null');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
