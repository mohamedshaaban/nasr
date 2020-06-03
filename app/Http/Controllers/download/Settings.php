<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
use App\Common;

/**
 * App\Models\Settings
 *
 * @property int $id
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $twitter
 * @property string|null $loginimage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $agreed_delivery_time
 * @property string|null $location_en
 * @property string|null $location_ar
 * @property string|null $phone
 * @property string|null $email
 * @property-read mixed $location
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereAgreedDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereLocationAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereLocationEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereLoginimage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Settings extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table = 'settings';

    protected $fillable =['loginimage','facebook','instagram','twitter','agreed_delivery_time','location_en','location_ar','phone','email','loyatly_point'];

    public function getLoginimageAttribute($loginimage)
    {
        return   asset('/uploads/'.$loginimage);

    }

    public function getLocationAttribute()
    {
        return Common::nameLanguage($this->location_en, $this->location_ar);
    }

    public function getDummyStringAttribute()
    {
        return Common::nameLanguage($this->dummy_string_en, $this->dummy_string_ar);
    }




}