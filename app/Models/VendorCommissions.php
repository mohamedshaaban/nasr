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
class VendorCommissions extends Model
{
//    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $table = 'vendor_commissions';
    protected $fillable=['fixed','precentage','vendor_id'];
//    protected $dates = ['deleted_at'];
    public function vendors()
    {

        return $this->belongsTo(Vendors::class,'vendor_id');

    }
}
