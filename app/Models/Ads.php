<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Sliders
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sliders whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ads extends Model
{
    protected $table='ads';
    public const ADS_ENABLED = 1;
    public const ADS_DISABLED = 0;

    protected $fillable = [
       'title_en','title_ar','image' , 'image_ar' , 'status','link','is_top'
    ];


    public static function getEnabledAds()
    {
        return Ads::whereStatus(Ads::ADS_ENABLED)->get();
    }
}
