<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderProduct extends Model
{
    protected $table='order_products';
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'sub_total', 'total','extraoption'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendors::class);
    }

}
