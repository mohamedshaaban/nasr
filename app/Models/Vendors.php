<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Product;
use App\Common;
use Illuminate\Database\Eloquent\SoftDeletes;
	

class Vendors extends Authenticatable
{
//    use HasApiTokens, Notifiable;
use SoftDeletes;
protected $dates = ['deleted_at'];

    protected $table = 'vendors';
    public const VENDOR_FULL_ACCESS = 1 ;
    public const VENDOR_PRODUCT_ACCESS = 2 ;
     public const VENDOR_SALES_ACCESS = 3 ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
          'name'
    ];
    protected $fillable = [
        'name', 'email', 'password',  'code' ,'activatecode','name_ar' ,'overview_en','overview_ar' ,'logo','parent_id' ,'is_active' ,'is_approved', 'country_id','permission', 'address', 'governorate_id','phone','latitude','longitude'
    ];

    public function getNameAttribute()
    {

        return Common::nameLanguage($this->attributes['name']  , $this->attributes['name_ar']);
    }

    /**
     *
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function product()
    {
        return $this->hasMany(Product::class ,'vendor_id');
    }
    public function productoffer()
    {
        return $this->hasMany(ProductOffer::class);
    }
    public function orderproduct()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function vendorareadeliverycharges()
    {
        return $this->hasMany(VendorAreaDeliveryCharges::class,'vendor_id');
    }
    public function subvendors()
    {
        return $this->hasMany(Vendors::class,'parent_id');
    }
    public function parentvendor()
    {
        return $this->belongsTo(Vendors::class,'parent_id');
    }
    public function vendorBankInformation()
    {
        return $this->hasMany(VendorBankInformation::class ,'vendor_id');
    }
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'vendor_areas', 'vendor_id', 'area_id')
            ->withPivot('delivery_charge');
    }
    public function countries()
    {
        return $this->belongsTo(Countries::class ,'country_id' );
    }
    public function vendorcommissions()
    {
        return $this->hasMany(VendorCommissions::class,'vendor_id');
    }
    public function vendororderdeliverycharges()
    {
        return $this->hasMany(VendorOrderDeliveryCharges::class,'vendor_id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class,'governorate_id');
    }
}
