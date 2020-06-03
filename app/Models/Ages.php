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
class Ages extends Model
{
    protected $table='ages';
    protected $fillable = [
        'name_en','name_ar'
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_ages', 'id', 'ages_id');
    }


    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }
}
