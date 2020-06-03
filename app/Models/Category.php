<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;
use function GuzzleHttp\json_decode;
class Category extends Model
{
    protected $appends =['name'];
    protected $fillable = [
        'name_en', 'name_ar', 'description_en', 'description_ar',
       'sort_order', 'status', 'parent_id','sort'
    ];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('hide_filter',0);
    }
}
