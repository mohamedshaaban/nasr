<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;

/**
 * App\Http\Models\FestivityWorks
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\FestivityWorks whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BastaatWorks extends Model
{
    protected $table='bastaat_works';
    public const BastaatWorks_ENABLED = 1;
    public const BastaatWorks_DISABLED = 0;
    public function getImageAttribute($image)
    {
        return asset( '/uploads/' . $image);
    }
    public static function getEnabledBastaatWorks()
    {
        return BastaatWorks::whereStatus(BastaatWorks::BastaatWorks_ENABLED)->get();
    }

    public function getTitleAttribute()
    {
        return Common::nameLanguage($this->title_en, $this->title_ar);
    }
}
