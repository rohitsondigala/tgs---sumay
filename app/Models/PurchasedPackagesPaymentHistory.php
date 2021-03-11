<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasedPackagesPaymentHistory extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'uuid', 'user_uuid', 'package_uuid', 'stream_uuid', 'subject_uuid','p_p_uuid', 'purchase_date', 'expiry_date', 'duration_in_days','price','payment_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }}
