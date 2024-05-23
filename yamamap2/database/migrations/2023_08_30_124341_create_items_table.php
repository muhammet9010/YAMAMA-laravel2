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
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('photo')->nullable()->default(0);
      $table->tinyInteger('active');
      $table->unsignedBigInteger('categori_id');
      $table->foreign('categori_id')->references('id')->on('item_categories');
      $table->float('gumla_price_tl');
      $table->float('gumla_price_usd');
      $table->float('old_gumla_price_tl');
      $table->float('old_gumla_price_usd');
      $table->integer('count')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('items');
  }
};
