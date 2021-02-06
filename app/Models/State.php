<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'country_id','name'
    ];

    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
}
