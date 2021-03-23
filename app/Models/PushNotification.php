<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotification extends Model
{
    use SoftDeletes,HasFactory,HasEvents;
    protected $fillable = [
        'uuid','user_uuid','title','image','description','type','student','professor'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });

    }
    public function getFullImagePathAttribute()
    {
        if(!empty($this->image)){
            return env('APP_URL').$this->image;
        }else{
            return null;
        }
    }
}
