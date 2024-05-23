<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outlay extends Model
{
  use HasFactory;
  protected $table = "outlays";
  protected $fillable = [
    'type', 'currency', 'total', 'user_id', 'active' , 'date'
    ,'status' //0 اداع    
              // سحب 1    

  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id'); 
  }
  public function outlayCategory()
  {
    return $this->belongsTo(outlay_categori::class, 'type', 'id');
  }
}
