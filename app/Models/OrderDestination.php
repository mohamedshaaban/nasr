<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDestination extends Model
{
    use SoftDeletes;
    protected $table = 'order_destination';
    protected $fillable=['title'];

}
