<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderProduct;
use App\Models\Coupon;
use App\Models\CustomerGiftCards;
use App\Models\OrderTrack;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    const VISA_PAYMENT_METHOD = 1 ;
    const MASTER_PAYMENT_METHOD = 2 ;
    const KNET_PAYMENT_METHOD = 3 ;
    const CASH_ON_DELIVERY_PAYMENT_METHOD = 4 ;
use SoftDeletes;
    protected $appends =['customername'];

    protected $fillable = [
        'user_id',  'address_id',  'unique_id',  'order_date',
        'sub_total',  'delivery_charges',  'total',  'is_paid',  'payment_method',
        'shipping_method','order_requester','order_destination','order_category','order_extra'
    ];
    public function getCustomernameAttribute()
    {

        if( $this->is_guest==1)
        {
            return $this->guestusers()->first()->name;
        }
        if($this->user()->first())
    {
        return $this->user()->first()->name;
    }
       return $this->useraddress()->first()->first_name.' '.$this->useraddress()->first()->second_name ;
    }


    public static function getWithRelations($id = null, $clone = false)
    {
        $orders =  self::with([
            'orderProducts',
            'orderProducts',
            'user',
            'userAddress',
            'orderDestination',
            'orderRequesters',
            'category'
        ]);

        if ($clone) {
            return $orders;
        }
        if ($id) {
            return $orders->find($id);
        }

        return $orders->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function guestusers()
    {
        return $this->belongsTo(GuestUsers::class , 'user_id');
    }
    public function orderstatus()
    {
        return $this->belongsTo(OrderStatus::class ,'status_id');
    }
    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethods::class, 'shipping_method');
    }

    public function ordertrack()
    {
        return $this->hasMany(OrderTrack::class);
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'status_id');
    }
    public function paymentmethods()
    {
        return $this->belongsTo(PaymentMethods::class,'payment_method');
    }
    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }
        public function ordertransactions()
    {
        return $this->hasMany(OrderTransactions::class);
    }
    public function vendororderdeliverycharges()
    {
        return $this->hasMany(VendorOrderDeliveryCharges::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'order_category');
    }

    public function orderRequesters()
    {
        return $this->belongsTo(OrderRequesters::class,'order_requester');
    }

    public function orderDestination()
    {
        return $this->belongsTo(OrderDestination::class,'order_destination');
    }
}
