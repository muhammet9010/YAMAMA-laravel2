<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtWithdraw extends Model
{
    use HasFactory;
    protected $table = "debt_withdraw";
    protected $fillable = [
        'debtor_id',
        'user_id',
        'price_tl',
        'price_usd',
    ];

    public function user()
    {
      return $this->belongsTo(User::class, 'user_id'); 
    }

    public function debtor()
    {
      return $this->belongsTo(Debtor::class, 'debtor_id'); 
    }

}
