<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class VendorBankInformation extends Model
{
    protected $fillable = [
        'id', 'account_name','address',
        'bank_id', 'account_number',
        'iban', 'vendor_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function banks()
    {
        return $this->belongsTo(Banks::class);
    }

}
