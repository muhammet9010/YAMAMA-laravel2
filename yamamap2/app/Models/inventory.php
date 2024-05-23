<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
  use HasFactory;
  protected $table = "inventories";
  protected $fillable = [
    'user_id',
    'item_id',
    'count',
    'real_count',
    'price_tl',
    'price_usd'
  ];

  public function item()
  {
    return $this->belongsTo(Item::class, 'item_id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
