<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotesFiles extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','note_uuid','file_name','file_type','file_mime_type','file_size','file_path','status'
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
}
