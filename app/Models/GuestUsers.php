<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserAddress;

class GuestUsers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table='guest_users';
    protected $fillable = [
        'name', 'email','first_name','last_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function userAddress()
    {
        return $this->hasMany(UserAddress::class,'user_id')->where('is_guest','=', 1);;
    }
    public function order()
    {
        return $this->hasMany(Order::class,'user_id');
    }
}
