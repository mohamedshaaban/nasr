<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Common;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Governorate
 *
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Area[] $areas
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Governorate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Governorate extends Model
{
//    use SoftDeletes;
//
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
//    protected $dates = ['deleted_at'];
protected $fillable=['name_en','name_ar','country_id'];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }

    public function areas()
    {
        if (app()->getLocale() == 'ar') {
            return $this->hasMany(Area::class)->orderBy('name_ar');
        }

        return $this->hasMany(Area::class)->orderBy('name_en');

    }
}
