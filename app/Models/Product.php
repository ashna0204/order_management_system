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

public function getImageUrlAttribute()
{
    if (!$this->image) {
        return asset('images/default-product.png'); // fallback image
    }
    return asset('storage/products/medium_' . $this->image);
}

  
}
