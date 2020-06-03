<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Currency extends Model
{

    protected $fillable = [
        'name_en', 'name_ar', 'code', 'symbol_en' ,'symbol_ar', 'value', 'status'
    ];
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Get the country record associated with the currency.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
