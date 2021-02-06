<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForgotPasswordOtp extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','otp','status','attempt'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_uuid','uuid');
    }
}
