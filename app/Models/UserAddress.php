<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Countries;
use App\Models\Provience;
class UserAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table='user_address';
    public const BILLINGADDRESSTYPE = 1;
    public const  SHIPPINGADDRESSTYPE = 2;
    protected $fillable = [
         'address_type','first_name','second_name','phone_no','fax','city' ,'company' ,'zip_code','type','user_id' ,'is_default','country_id' , 'block' , 'street' , 'avenue' , 'floor' , 'flat' , 'extra_direction', 'governate_id','area_id','is_guest'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function guestusers()
    {
        return $this->belongsTo(GuestUsers::class);
    }
    public function countries()
    {
        return $this->belongsTo(Countries::class,'country_id' ,'id' );
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class,'governate_id' ,'id' );
    }
    public function area()
    {
        return $this->belongsTo(Area::class,'area_id' ,'id' );
    }
}
