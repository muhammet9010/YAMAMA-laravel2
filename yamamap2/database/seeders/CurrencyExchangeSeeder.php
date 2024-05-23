<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('currency_exchange')->insert([
        'currency_type' => '1',
        'actual_amount' => 100.0,
        'equivalent_amount' => 400.0,
        'user_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('currency_exchange')->insert([
        'currency_type' => '2',
        'actual_amount' => 150.0,
        'equivalent_amount' => 600.0,
        'user_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }
}
