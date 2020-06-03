<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name_en', 'name_ar','image', 'top',
         'home', 'sort_order', 'status'
    ];
}
