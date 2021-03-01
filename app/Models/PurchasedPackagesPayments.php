<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasedPackagesPayments extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'uuid', 'purchase_package_uuid', 'payment_id', 'price', 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }
}
