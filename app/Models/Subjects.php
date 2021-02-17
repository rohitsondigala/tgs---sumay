<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjects extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','stream_uuid','title','slug','status'
    ];

    protected $appends = ['is_selected'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function stream(){
        return $this->belongsTo('App\Models\Streams','stream_uuid','uuid');
    }

    public function purchase_packages(){
        return $this->hasMany('App\Models\PurchasedPackage','uuid','subject_uuid');
    }

    public function getIsSelectedAttribute(){
        return 0;
    }
}
