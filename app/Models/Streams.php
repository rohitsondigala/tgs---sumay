<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Streams extends Model
{
    use SoftDeletes,HasFactory,HasEvents;
    protected $fillable = [
        'uuid','title','slug','status','is_standard'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });

    }

    public function subjects(){
        return $this->hasMany('App\Models\Subjects','stream_uuid','uuid');
    }
}
