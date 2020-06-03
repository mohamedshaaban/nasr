<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;
use App\Models\DistanceGroups;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethods extends Model
{
 
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function getTitleAttribute()
    {
        return Common::nameLanguage($this->title_en, $this->title_ar);
    }
    public function order() {
        return $this->belongsTo( Order::class ,'payment_method' );
    }
}
