<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\OrderStatus;
class OrderTrack extends Model
{
    protected $table = 'order_track';
    protected $fillable=['vendor_id','order_id','order_status_id'];
    public function order()
    {
        return $this->belongsToMany(Order::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendors::class,'vendor_id');
    }
    public function orderstatus()
    {
        return $this->hasOne(OrderStatus::class,'id','order_status_id');
    }  
}
