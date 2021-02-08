<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'uuid','uuid','role_uuid','name','username','mobile','email','email_verified_at','password','country','state','city','image'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role(){
        return $this->belongsTo('App\Models\UsersRoles','role_uuid','uuid');
    }

    public function country_detail(){
        return $this->belongsTo('App\Models\Country','country','id');
    }

    public function state_detail()
    {
        return $this->belongsTo('App\Models\State', 'state', 'id');
    }

    public function city_detail(){
        return $this->belongsTo('App\Models\City','city','id');
    }
    public function user_otp(){
        return $this->belongsTo('App\Models\UsersOtp','uuid','user_uuid');
    }
    public function student_subjects(){
        return $this->hasMany('App\Models\StudentSubjects','user_uuid','uuid');
//        return $this->hasManyThrough('App\Models\Subjects','App\Models\StudentSubjects','user_uuid','uuid','uuid');
    }
    public function student_detail(){
        return $this->hasMany('App\Models\StudentDetails','uuid','user_uuid');
    }
    public function professor_subjects(){
        return $this->hasMany('App\Models\ProfessorSubjects','user_uuid','uuid');
//        return $this->hasManyThrough('App\Models\Subjects','App\Models\StudentSubjects','user_uuid','uuid','uuid');
    }
    public function professor_detail(){
        return $this->hasMany('App\Models\ProfessorDetails','uuid','user_uuid');
    }

    public function moderator_daily_posts(){
        return $this->hasMany('App\Models\ModeratorDailyPost','uuid','user_uuid');
    }

    public function scopeOfRole($query,$title){
        return $query->whereHas('role',function ($query) use ($title){
            $query->where('title',$title);
        });
    }
    public function moderator(){
        return $this->belongsTo('App\Models\ModeratorSubject','uuid','user_uuid');
    }


}
