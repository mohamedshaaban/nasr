<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Character;

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
class Product extends Model
{
    protected $appends = [
        'is_new', 'main_image_path',
        'images_path',  'name'
    ];
    protected $fillable = [
        'id', 'name_en', 'name_ar', 'slug_name', 'sku',
        'short_description_en', 'short_description_ar', 'description_en',
        'description_ar', 'price', 'quantity',
        'main_image', 'images', 'status',
        'vendor_id','product_type',
        'delivery_and_return_en', 'delivery_and_return_ar','code'

    ];
    public function breeds()
    {
        return $this->belongsToMany(Breeds::class ,'product_breeds' ,'product_id','breeds_id'  );
    }
    public function ages()
    {
        return $this->belongsToMany(Ages::class ,'product_ages' ,'product_id','ages_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Colors::class ,'product_colors' ,'product_id','colors_id');
    }
    public function genders()
    {
        return $this->belongsToMany(Genders::class ,'product_genders' ,'product_id','genders_id');
    }
    public function productsizes()
    {
        return $this->hasMany(ProductSizes::class  );
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }
    public function requesters()
    {
        return $this->belongsToMany(OrderRequesters::class, 'product_requesters', 'product_id', 'requester_id');
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class ,'product_id');
    }
    public function offer()
    {
        return $this->hasOne(ProductOffer::class)
            ->whereDate('from', '<=', date('Y-m-d'))
            ->whereDate('to', '>=', date('Y-m-d'));
    }

    public function vendor()
    {
        return $this->belongsTo(Vendors::class);
    }
    public function orderproduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }


    public function setImagesAttribute($images)
    {
        if (is_array($images)) {
            $this->attributes['images'] = json_encode($images);
        }
    }

    public function getImagesAttribute($pictures)
    {

        if ($pictures == "" || json_decode($pictures, true) == null) {
            return [];
        }

        return json_decode($pictures, true);
    }

    public function getMainImagePathAttribute()
    {
        if (substr($this->main_image, 0, 6) == 'https:') {
            return $this->main_image;
        } else {
            if (!is_null($this->main_image) && file_exists(public_path() . '/uploads/' . $this->main_image)) {
                return url('/') . '/uploads/' . $this->main_image;
            }
            return  url('/') . '/uploads/no-image-white-standard.png';
        }
    }

    public function getImagesPathAttribute()
    {

        if (count($this->images) == 0) {
            return [];
        }
        $images =  $this->images;

        $imagesPath = [];
        foreach ($images as $image) {
            if (substr($image, 0, 6) == 'https:') {
                $imagesPath[] =  $image;
            } else {
                if (!is_null($image) && file_exists(public_path() . '/uploads/' . $image)) {
                    $imagesPath[] =  url('/') . '/uploads/' . $image;
                } else {
                    $imagesPath[] =   url('/') . '/uploads/no-image-white-standard.png';
                }
            }
        }

        return $imagesPath;
    }

    public function getIsNewAttribute()
    {
//        return $this->created_at >= \Carbon\Carbon::now()->subDays(app('settings')->new_arrival_days)->toDateTimeString() ? true : false;
    }


    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }

    public function getDescriptionAttribute()
    {
        return Common::nameLanguage($this->description_en, $this->description_ar);
    }

}
