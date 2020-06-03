<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Common;

class OrderStatus extends Model {
	protected $table = 'order_status';

	public function order() {
		return $this->hasMany( Order::class );
	}

	public function ordertrack() {
		return $this->hasOne( OrderTrack::class );
	}
    public function getTitleAttribute()
    {
        return Common::nameLanguage($this->title_en, $this->title_ar);
    }
}
