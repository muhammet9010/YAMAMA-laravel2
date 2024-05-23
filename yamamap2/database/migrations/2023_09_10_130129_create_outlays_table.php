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
    Schema::create('outlays', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('currency');
      $table->integer('total');
      $table->boolean('status')->nullable();
      $table->date('date')->default(now());
      $table->tinyInteger('active');
      $table->unsignedBigInteger('type')->nullable();
      $table->foreign('type')->references('id')->on('outlay_categoris');
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('outlays');
  }
};