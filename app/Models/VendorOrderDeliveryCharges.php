<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\VendorAreaDeliveryCharges
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorAreaDeliveryCharges newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorAreaDeliveryCharges newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorAreaDeliveryCharges query()
 * @mixin \Eloquent
 */
class VendorOrderDeliveryCharges extends Model
{
//    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $table = 'vendor_order_delivery_charges';
//    protected $dates = ['deleted_at'];
    protected $fillable = [
        'delivery_charges', 'vendor_id', 'area_id','order_id','total','commission_kd','commission_percentage','transferred'
    ];
    public function vendors()
    {

        return $this->belongsTo(Vendors::class ,'vendor_id');

    }
    public function order()
    {

        return $this->belongsTo(Order::class ,'order_id');

    }
}