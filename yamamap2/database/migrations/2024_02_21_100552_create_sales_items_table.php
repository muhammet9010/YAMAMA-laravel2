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
        Schema::create('sales_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('currency_id');
            $table->decimal('weight', 10, 2);
            $table->decimal('price', 10, 2);
            $table->date('date')->default(now());
            $table->foreign('sales_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_items');
    }
};
