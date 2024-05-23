<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
  use HasFactory;
  protected $table = "orders";
  protected $fillable = [
    'user_id',  'notes', 'accept', 'wait','status','total_Doler','total_Lera'
  ];

  public function user()
  {
    return $this->belongsTo(User::class ,'user_id','id');
  }
  public function item()
  {
    return $this->belongsTo(Item::class,'item_id','id');
  }
}
