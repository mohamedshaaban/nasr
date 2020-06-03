<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
use App\Common;

/**
 * App\Models\Posts
 *
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string $description_ar
 * @property string $description_en
 * @property int $category_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Posts whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Posts extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'posts';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar', 'name_en','description_ar','description_en','status'
    ];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }

}
