<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;



class Product extends Model
{

    protected $fillable = ['name', 'price','image'];

   public function items() {
    return $this->hasMany(OrderItem::class);
}
  
}
