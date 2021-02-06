<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDetails extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','university_name','college_name','preferred_language','other_information'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }
}
