<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubjects extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','stream_uuid','subject_uuid'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subjects','subject_uuid','uuid');
    }
}
