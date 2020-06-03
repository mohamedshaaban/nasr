<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Character;
use App\Models\Brand;
use App\Models\ProductReview;
use App\Models\ProductsTogetherPrice;
use App\Common;
use function GuzzleHttp\json_decode;
use App\Models\Vendors;
use App\Models\Ages;
use App\Models\Breeds;
use App\Models\Genders;
use App\Models\Colors;
use App\Models\Sizes;
use Illuminate\Database\Eloquent\Relations\Relation;
class ProductSizes extends Model
{
    protected $fillable = [
       'product_id' ,'sizes_id', 'description','price'
    ];

    public function sizes()
    {
        return $this->belongsToMany(Sizes::class);
    }
    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
}
