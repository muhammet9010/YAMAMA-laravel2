<?php

namespace App\Models;

use App\Models\Item;
use App\Models\priceCategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PriceCategoryItem extends Model
{
  use HasFactory;

  protected $table = 'price_category_item'; // اسم الجدول الوسيط
  protected $fillable = ['price_categoris_id', 'items_id', 'percent_sud', 'percent_tl']; // الأعمدة الممكن تعديلها

  public function price_category()
  {
    return $this->belongsTo(priceCategori::class, 'price_categoris_id', 'id');
  }
}
