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
    use SoftDeletes,HasFactory,Notifiable;
    protected $fillable = [
        'uuid','uuid','role_uuid','name','username','mobile','email','email_verified_at','password','country','state','city','image','university_name','college_name','stream_uuid'
    ];
    protected $appends = ['full_image_path'];

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

    public function stream(){
        return $this->hasOne('App\Models\Streams','uuid','stream_uuid');
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
        return $this->hasMany('App\Models\PurchasedPackages','user_uuid','uuid');
    }
    public function student_subjects_all(){
        return $this->hasManyThrough('App\Models\Subjects','App\Models\PurchasedPackages','user_uuid','uuid','uuid');
    }
    public function student_detail(){
        return $this->hasOne('App\Models\StudentDetails','user_uuid','uuid');
    }
    public function professor_subjects(){
        return $this->hasManyThrough('App\Models\Subjects','App\Models\ProfessorSubjects','user_uuid','uuid','subject_uuid','uuid');
    }
    public function professor_detail(){
        return $this->hasOne('App\Models\ProfessorDetails','user_uuid','uuid');
    }

    public function moderator_daily_posts(){
        return $this->hasMany('App\Models\ModeratorDailyPost','uuid','user_uuid');
    }

    public function scopeOfRole($query,$title){
        return $query->whereHas('role',function ($query) use ($title){
            $query->where('title',$title);
        });
    }
    public function scopeOfVerify($query){
        return $query->where('verify',1);
    }
    public function moderator(){
        return $this->belongsTo('App\Models\ModeratorSubject','uuid','user_uuid');
    }


        public function getFullImagePathAttribute()
        {
            if(!empty($this->image)){
                return env('APP_URL').$this->image;
            }else{
                return null;
            }
        }
}
