<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    

    protected $fillable = ['user_id','customer_name', 'address', 'date', 'status'];



   public function items() {
    return $this->hasMany(OrderItem::class);

    
}
public function user(){
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(){
        switch ($this->status){
            case 'pending':
                return '<span class="badge bg-secondary">Pending</span>';
            case 'confirmed':
                return '<span class="badge bg-primary">Confirmed</span>';
            case 'processing':
                return '<span class="badge bg-secondary">Processing</span>';
            case 'shipped':
                return '<span class="badge bg-warning text-danger">Shipped</span>';
            case 'delivered':
                return '<span class="badge bg-success">Delivered</span>';
            case 'cancelled':
                return  '<span class= "badge bg-danger">Cancelled</span>';
            default:
            return '<span class= "badge bg-light">unknown</span>';
        }
    }

}

