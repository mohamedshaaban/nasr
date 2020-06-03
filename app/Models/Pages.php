<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
use App\Common;

/**
 * App\Models\Pages
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $title_en
 * @property string|null $value_en
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $title_ar
 * @property string|null $value_ar
 * @property-read mixed $body
 * @property-read mixed $title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereTitleAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereValueAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pages whereValueEn($value)
 * @mixin \Eloquent
 */
class Pages extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'pages';

    public function getImageAttribute($image)
    {
        return   asset('uploads/'.$image);

    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','name_en', 'description_en','image','name_ar','description_ar'
    ];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }
    public function getDescriptionAttribute()
    {
        return Common::nameLanguage($this->description_en, $this->description_ar);
    }
}
