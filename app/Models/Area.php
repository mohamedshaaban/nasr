<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;
use App\Models\DistanceGroups;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Area
 *
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string $status
 * @property int $governorate_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name
 * @property-read \App\Models\Governorate $governorate
 * @property-read \App\Models\UserAddress $userAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vendor[] $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereGovernorateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
    protected $fillable=['name_en','name_ar','governorate_id'];
//    use SoftDeletes;
//    protected $dates = ['deleted_at'];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function vendor()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_areas', 'area_id', 'vendor_id');
    }
    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function distanceGroups()
    {
        return $this->belongsToMany(DistanceGroups::class, 'distance_area_groups', 'area_id', 'distance_group_id');
    }
}
