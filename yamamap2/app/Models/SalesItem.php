<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
  use HasFactory;
  protected $table = "sales_items";
  protected $fillable = [
    'sales_id', 'product_id', 'currency_id', 'weight', 'price','date'
  ];

  public function sales()
  {
    return $this->belongsTo(sales::class);
  }
  public function product()
  {
    return $this->belongsTo(Item::class);
  }
}
