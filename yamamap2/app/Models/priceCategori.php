<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class priceCategori extends Model
{
  use HasFactory;
  protected $table = "price_categoris";
  protected $fillable = [
    'id', 'name',  'active',
  ];
  public function items()
  {
    return $this->belongsToMany(Item::class, 'price_category_item', 'price_categoris_id', 'items_id')
      ->withPivot('percent_sud' , 'percent_tl');
  }
  public function user()
  {
    return $this->hasMany(User::class, 'price_categoris_id', 'id');
  }
}
