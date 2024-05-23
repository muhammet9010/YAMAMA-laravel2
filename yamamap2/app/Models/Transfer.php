<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
  use HasFactory;
  protected $table = "transfers";
  protected $fillable = [
    'sender_id', 'recipient_id','admin_id', 'inventories_id', 'count','name_item'
  ];
  public function sender()
  {
    return $this->belongsTo(User::class, 'sender_id');
  }
  public function recipient()
  {
    return $this->belongsTo(User::class, 'recipient_id');
  }
  public function inventories()
  {
    return $this->belongsTo(inventory::class, 'inventories_id');
  }
  public function admin()
  {
    return $this->belongsTo(User::class, 'admin_id');
  }
}
