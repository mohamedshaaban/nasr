<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
class Coupon extends Model
{
    public function order()
    {
        return $this->belongsToMany(Order::class);
    }  
}
