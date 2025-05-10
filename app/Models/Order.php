<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    

    protected $fillable = ['user_id','customer_name', 'address', 'date'];



   public function items() {
    return $this->hasMany(OrderItem::class);

    
}
public function user(){
        return $this->belongsTo(User::class);
    }

}

