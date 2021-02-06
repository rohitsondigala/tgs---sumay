<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeratorDailyPost extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','moderator_subject_uuid','title','slug','description','status','image'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function scopeOfUser($query){
        return $query->where('user_uuid',auth()->user()->user_uuid);
    }
}
