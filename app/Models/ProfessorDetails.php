<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessorDetails extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','university_name','college_name','preferred_language','other_information','education_qualification','research_of_expertise','achievements'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }
}
