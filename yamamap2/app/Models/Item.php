<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
  use HasFactory;
  protected $table = "items";
  protected $fillable = [
    'name',
    'photo',
    'active',
    'categori_id',
    'gumla_price_tl',
    'gumla_price_usd',
    'old_gumla_price_tl',
    'old_gumla_price_usd'
  ];
  public function priceCategories()
  {
    return $this->belongsToMany(priceCategori::class, 'price_category_item', 'items_id', 'price_categoris_id')
      ->withPivot('percent_sud' , 'percent_tl');
  }

  public function category()
  {
    return $this->belongsTo(ItemCategory::class, 'categori_id');
  }
  public function order()
  {
    return $this->hasMany(Orders::class, 'item_id', 'id');
  }
}
