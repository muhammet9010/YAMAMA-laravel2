<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;
  use HasRoles;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

  protected $fillable = [
    'name',
    'img',
    'email',
    'password',
    'device_key',
    'role',
    'phone',
    'address',
    'boxTl',
    'boxUsd',
    'invantory',
    'outlay',
    'salary',
    'status',
    'roles_name',
    'photo',
    'remember_token',
    'created_at',
    'updated_at',
    'account_number',
    'price_categoris_id',
    'device_key',
    'is_active'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'roles_name' => 'array'
  ];

  public function sales()
  {
    return $this->belongsTo(sales::class, 'user_id', 'id');
  }
  public function inventories()
  {
    return $this->belongsTo(inventory::class, 'user_id', 'id');
  }
  public function outlay()
  {
    return $this->hasMany(outlay::class);
  }
  public function debts()
  {
    return $this->belongsTo(Debts::class, 'user_id', 'id');
  }
  public function orders()
  {
    return $this->belongsTo(Orders::class,  'user_id', 'id');
  }
  public function priceCategories()
  {
    return $this->belongsTo(priceCategori::class, 'id', 'price_categoris_id');
  }
}
