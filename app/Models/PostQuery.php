<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostQuery extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','from_user_uuid','to_user_uuid','stream_uuid','subject_uuid','title','slug','description','approve'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function from_user(){
        return $this->belongsTo('App\Models\User','from_user_uuid','uuid');
    }
    public function to_user(){
        return $this->belongsTo('App\Models\User','to_user_uuid','uuid');
    }
    public function subject()
    {
        return $this->belongsTo('App\Models\Subjects', 'subject_uuid', 'uuid');
    }
    public function stream()
    {
        return $this->belongsTo('App\Models\Streams', 'stream_uuid', 'uuid');
    }
    public function post_reply()
    {
        return $this->hasOne('App\Models\PostQueryReply', 'post_query_uuid', 'uuid');
    }

    public function image_files(){
        return $this->hasMany('App\Models\PostQueryFiles','post_query_uuid','uuid')->where('file_type','IMAGE');
    }
    public function audio_files(){
        return $this->hasMany('App\Models\PostQueryFiles','post_query_uuid','uuid')->where('file_type','AUDIO');
    }
    public function pdf_files(){
        return $this->hasMany('App\Models\PostQueryFiles','post_query_uuid','uuid')->where('file_type','PDF');
    }
}
