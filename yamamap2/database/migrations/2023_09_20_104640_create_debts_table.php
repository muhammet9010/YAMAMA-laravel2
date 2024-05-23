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
    Schema::create('debts', function (Blueprint $table) {
      $table->id();
      $table->integer('count');
      $table->float('price_usd');
      $table->float('price_tl');
      $table->tinyInteger('paid');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('item_id');
      $table->unsignedBigInteger('debtor_id');
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('item_id')->references('id')->on('items');
      $table->foreign('debtor_id')->references('id')->on('debtors');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};