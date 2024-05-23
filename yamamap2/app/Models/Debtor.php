<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    use HasFactory;
    protected $table = "debtors";
    protected $fillable = [
        'name',
        'id_number',
        'user_id',
        'total_debtor_box_tl',
        'total_debtor_box_usd',
    ];

    public function debts()
    {
        return $this->hasMany(Debts::class, 'debtor_id');
    }
    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'debts', 'debtor_id', 'user_id')
    //         ->withPivot(['count', 'price_usd', 'price_tl', 'paid'])
    //         ->withTimestamps();
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
