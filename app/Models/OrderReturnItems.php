<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturnItems extends Model
{
     protected $table = 'order_return_items';
    protected $fillable=['product_id','order_id','quantity','status'];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
