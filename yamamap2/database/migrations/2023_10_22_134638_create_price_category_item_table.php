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
    Schema::create('price_category_item', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('price_categoris_id');
      $table->unsignedBigInteger('items_id');
      $table->float('percent_sud')->nullable(); // تعيين الحقل ليكون قابلاً للقبول للقيم الفارغة
      $table->float('percent_tl')->nullable(); // تعيين الحقل ليكون قابلاً للقبول للقيم الفارغة
      $table->timestamps();

      $table->foreign('items_id')->references('id')->on('items')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table->foreign('price_categoris_id')->references('id')->on('price_categoris')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('price_category_item');
  }
};
