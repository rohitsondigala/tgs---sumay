<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostQueryReply extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','post_query_uuid','description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function post_query(){
        return $this->belongsTo('App\Models\PostQuery','post_query_uuid','uuid');
    }

    public function image_files(){
        return $this->hasMany('App\Models\PostQueryReplyFiles','post_reply_uuid','uuid')->where('file_type','IMAGE');
    }
    public function audio_files(){
        return $this->hasMany('App\Models\PostQueryReplyFiles','post_reply_uuid','uuid')->where('file_type','AUDIO');
    }
    public function pdf_files(){
        return $this->hasMany('App\Models\PostQueryReplyFiles','post_reply_uuid','uuid')->where('file_type','PDF');
    }

}
