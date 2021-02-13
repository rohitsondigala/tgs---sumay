<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packages extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','stream_uuid','subject_uuid','title','slug','description','status','image','price_month_3','price_month_6','price_month_12','price_month_24','price_month_36'
    ];
    protected $appends = ['full_image_path'];
    protected $hidden = ['image'];

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
    public function stream(){
        return $this->belongsTo('App\Models\Streams','stream_uuid','uuid');
    }
    public function getFullImagePathAttribute()
    {
        return env('APP_URL').$this->image;
    }
    public function purchase_detail()
    {
        return $this->hasMany('App\Models\PurchasedPackages', 'package_uuid', 'uuid');
    }
}
