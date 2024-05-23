<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {

     $this->call(PermissionTableSeeder::class);
    // // php artisan db:seed --class=PermissionTableSeeder
     $this->call(UsersTableSeeder::class);
    // php artisan db:seed --class=UsersTableSeeder
    $this->call(CurrencyExchangeSeeder::class);
    $this->call(outlay_categorySeeder::class);
  }
}
