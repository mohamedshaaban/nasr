<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;
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
class Sliders extends Model
{
    protected $table='sliders';
    public const SLIDER_ENABLED = 1;
    public const SLIDER_DISABLED = 0;

    protected $fillable = [
       'title_en','title_ar','image' , 'status','link'
    ];
    public function getTitleAttribute()
    {
        return Common::nameLanguage($this->title_en, $this->title_ar);
    }

    public static function getEnabledSliders()
    {
        return Sliders::whereStatus(Sliders::SLIDER_ENABLED)->get();
    }
}
