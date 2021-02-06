<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'state_id','name'
    ];

    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
    public function state(){
        return $this->belongsTo('App\Models\State','state_id','id');
    }
}
