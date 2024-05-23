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
    Schema::create('sales', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');
      $table->decimal('total_Doler', 10, 2);
      $table->decimal('total_Lera', 10, 2);
      $table->date('date')->default(now());
      $table->integer('debtor_id')->nullable();
      $table->tinyInteger('type')->default(1);
      $table->tinyInteger('active')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  }
};
