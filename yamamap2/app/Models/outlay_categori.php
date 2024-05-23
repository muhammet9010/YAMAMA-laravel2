<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outlay_categori extends Model
{
  use HasFactory;
  protected $table = "outlay_categoris";
  protected $fillable = [
    'id', 'name', 'active'
  ];

  public function outlays()
  {
    return $this->hasMany(outlay::class, 'type', 'id');
  }
}
