<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reviews extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','to_user_uuid','from_user_uuid','subject_uuid','rating','description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }
    public function from_user(){
        return $this->hasOne('App\Models\User','uuid','from_user_uuid');
    }

    public function to_user(){
        return $this->hasOne('App\Models\User','uuid','to_user_uuid');
    }
    public function subject(){
        return $this->hasOne('App\Models\Subjects','uuid','subject_uuid');
    }

}
