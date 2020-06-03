<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductOffer extends Model
{
    protected $fillable = [
        'id', 'product_id', 'value', 'from', 'to', 'is_fixed','fixed','percentage' ,'vendor_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function vendors()
    {
        return $this->belongsTo(Vendors::class);
    }
}
