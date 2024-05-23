<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
  use HasFactory;
  protected $table = "sales";
  protected $fillable = [
    'user_id', 'total_Doler','total_Lera', 'debtor_id','active','type','date'
  ];
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function debtor()
  {
    return $this->belongsTo(Debtor::class);
  }
}
