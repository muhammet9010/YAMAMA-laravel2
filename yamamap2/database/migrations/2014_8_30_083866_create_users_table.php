<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('img')->nullable();
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('device_key')->nullable();
      $table->integer('role')->nullable();
      $table->string('phone')->nullable();
      $table->string('address')->nullable();
      $table->float('boxTl')->nullable();
      $table->float('boxUsd')->nullable();
      $table->string('outlay')->nullable();
      $table->string('invantory')->nullable();
      $table->string('salary')->nullable();
      $table->string('account_number')->nullable();
      $table->boolean('is_active')->default(1);



      $table->string('password');
      $table->text('roles_name')->nullable();
      $table->string('status', 10)->nullable();
      $table->rememberToken();
      // $table->foreignId('current_team_id')->nullable();
      $table->string('profile_photo_path', 2048)->nullable();


      $table->unsignedBigInteger('price_categoris_id')->nullable();
      $table->foreign('price_categoris_id')
        ->references('id')
        ->on('price_categoris')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      // $table->foreign('price_categoris_id')->references('id')->on('price_categoris')
      //   ->onDelete('cascade')
      //   ->onUpdate('cascade');

      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['price_categori_id']);
    });

    Schema::dropIfExists('users');
  }
}
