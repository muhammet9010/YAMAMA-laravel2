<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDebt extends Model
{
  use HasFactory;
  protected $table = "cart_debts";
  protected $fillable = [
    'user_id', 'product_id', 'currency_id', 'debtor_id', 'weight', 'price'
  ];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function product()
  {
    return $this->belongsTo(Item::class);
  }

  public function debt()
  {
    return $this->belongsTo(Debtor::class);
  }
}
