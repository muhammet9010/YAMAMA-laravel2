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
        Schema::create('debt_withdraw', function (Blueprint $table) {
            $table->id();
            $table->float('price_usd');
            $table->float('price_tl');
            $table->unsignedBigInteger('user_id');
            $table->integer('debtor_id');

            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_withdraw');
    }
};
