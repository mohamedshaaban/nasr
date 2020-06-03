<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Faq
 *
 * @property int $id
 * @property int $category_id
 * @property string $question_ar
 * @property string $question_en
 * @property string $answer_ar
 * @property string $answer_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $category
 * @property-read mixed $answer
 * @property-read mixed $question
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereAnswerAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereAnswerEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereQuestionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereQuestionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CartStorage extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'cart_storage';


}