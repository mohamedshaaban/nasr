<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'name_en', 'name_ar', 'image', 'top',
        'sort_order', 'status'
    ];
}
