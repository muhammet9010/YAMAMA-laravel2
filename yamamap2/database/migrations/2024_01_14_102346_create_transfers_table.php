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
    Schema::create('transfers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('sender_id')->unsigned();
      $table->foreignId('recipient_id')->unsigned();
      $table->foreignId('admin_id')->unsigned();
      $table->foreignId('inventories_id')->unsigned();
      $table->integer('count');
      $table->string('name_item');

      $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('inventories_id')->references('id')->on('inventories')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transfers');
  }
};
