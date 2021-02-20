<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notes extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','user_uuid','stream_uuid','subject_uuid','title','slug','description','read_status','approved_by','reason','approve'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_uuid','uuid');
    }
    public function subject()
    {
        return $this->belongsTo('App\Models\Subjects', 'subject_uuid', 'uuid');
    }
    public function stream()
    {
        return $this->belongsTo('App\Models\Streams', 'stream_uuid', 'uuid');
    }

    public function image_files(){
        return $this->hasMany('App\Models\NotesFiles','note_uuid','uuid')->where('file_type','IMAGE');
    }
    public function audio_files(){
        return $this->hasMany('App\Models\NotesFiles','note_uuid','uuid')->where('file_type','AUDIO');
    }
    public function pdf_files(){
        return $this->hasMany('App\Models\NotesFiles','note_uuid','uuid')->where('file_type','PDF');
    }

    public function scopeOfApprove($q){
        return $q->where('approve',1);
    }
    public function scopeOfOrderBy($q,$order){
        return $q->orderBy('id',$order);
    }
}
