<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
use App\Common;

/**
 * App\Models\PostsCategories
 *
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostsCategories whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostsCategories extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'posts_categories';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar', 'name_en'
    ];
    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }

}
