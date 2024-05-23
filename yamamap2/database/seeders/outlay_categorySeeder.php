<?php

namespace Database\Seeders;

use App\Models\outlay_categori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class outlay_categorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('outlay_categoris')->delete();
    outlay_categori::create([
      'id' => 1,
      'name' => 'رواتب',
      'active' => 1
    ]);
    outlay_categori::create([
      'id' => 2,
      'name' => 'طلبيات',
      'active' => 1
    ]);
    outlay_categori::create([
      'id' => 3,
      'name' => 'موارد',
      'active' => 1
    ]);
    outlay_categori::create([
      'id' => 4,
      'name' => 'تسديد دين',
      'active' => 1
    ]);
    outlay_categori::create([
      'id' => 5,
      'name' => 'ايداع',
      'active' => 1
    ]);
  }
}
