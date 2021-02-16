<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasedPackages extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'uuid', 'user_uuid', 'package_uuid', 'stream_uuid', 'subject_uuid', 'purchase_date', 'expiry_date', 'duration_in_days', 'status','price','is_purchased','registration'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subjects', 'subject_uuid', 'uuid');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'uuid', 'user_uuid');
    }
    public function user_city_detail()
    {
        return $this->hasOneThrough('App\Models\City', 'App\Models\User','uuid','id','user_uuid');
    }

    public function stream()
    {
        return $this->belongsTo('App\Models\Streams', 'stream_uuid', 'uuid');
    }
    public function package()
    {
        return $this->belongsTo('App\Models\Packages', 'package_uuid', 'uuid');
    }

}
