<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchange extends Model
{
    use HasFactory;

    protected $table = "currency_exchange";
    protected $fillable = [
      'currency_type','actual_amount', 'equivalent_amount', 'user_id', 'created_at'
    ];
    public function user()
    {
      return $this->belongsTo(User::class ,'user_id','id');
    }
}
