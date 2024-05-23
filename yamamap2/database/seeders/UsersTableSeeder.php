<?php

namespace Database\Seeders;

// use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Illuminate\Tests\Integration\Database\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class UsersTableSeeder extends Seeder
{
  public function run()
  {
    $user = User::create([
      'name' => 'Fady ',
      'email' => 'admin@gmail.com',
      'email_verified_at' => now(),
      'device_key' => '1',
      'role' => 1,
      'phone' => '1234567890',
      'address' => 'egypt',
      'boxTl' => 100,
      'boxUsd' => 200,
      'outlay' => 50,
      'invantory' => 10,
      'salary' => 5000,
      'account_number' => '123456789',
      'price_categoris_id' => null,
      'password' => Hash::make('123456789'),
      'remember_token' => Str::random(10),
      // 'current_team_id' => 1,
      'status' => 'مفعل',
      'roles_name' => ['Admin'],
      'profile_photo_path' => 'path_to_profile_photo.jpg',
    ]);
    $role = Role::create(['name' => 'Admin']);

    $permissions = Permission::pluck('id', 'id')->all();

    $role->syncPermissions($permissions);

    // $user->assignRole([$role->id]);
    $user->assignRole([$role->name]);
  }
}
