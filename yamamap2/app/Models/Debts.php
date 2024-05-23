<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debts extends Model
{
    use HasFactory;
    protected $table = "debts";
    protected $fillable = [
        'debtor_id',
        'item_id',
        'user_id',
        'count',
        'paid',
        'price_tl',
        'price_usd',
    ];
    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'debtor_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    // الدالة التي تقوم بالبحث في الديون بناءً على user_id
    public function scopeFindByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getDebtsByUserId($userId)
    {
        $debts = Debts::where('user_id', $userId)->with('debtor')->get();

        return $debts;
    }


}
