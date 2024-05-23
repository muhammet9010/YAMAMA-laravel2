<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceCategorisTable extends Migration
{
  public function up()
  {
    Schema::create('price_categoris', function (Blueprint $table) {
      // $table->id();
      $table->bigIncrements('id');
      $table->string('name');
      $table->tinyInteger('active');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('price_categoris');
  }
}
