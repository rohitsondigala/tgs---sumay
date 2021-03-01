<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes,HasFactory,Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'uuid','uuid','role_uuid','name','username','mobile','email','email_verified_at','password','country','state','city','image','university_name','college_name','stream_uuid','access_token'
    ];
    /**
     * @var string[]
     */
    protected $appends = ['full_image_path'];

    /**
     * @var string[]
     */
    protected $touches = ['student_subjects'];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            addUUID($model);
        });
    }

    /**
     * @return mixed
     */
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

    /**
     * @return HasOne
     */
    public function stream(){
        return $this->hasOne('App\Models\Streams','uuid','stream_uuid');
    }

    /**
     * @return BelongsTo
     */
    public function role(){
        return $this->belongsTo('App\Models\UsersRoles','role_uuid','uuid');
    }

    /**
     * @return BelongsTo
     */
    public function country_detail(){
        return $this->belongsTo('App\Models\Country','country','id');
    }

    /**
     * @return BelongsTo
     */
    public function state_detail()
    {
        return $this->belongsTo('App\Models\State', 'state', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function city_detail(){
        return $this->belongsTo('App\Models\City','city','id');
    }

    /**
     * @return BelongsTo
     */
    public function user_otp(){
        return $this->belongsTo('App\Models\UsersOtp','uuid','user_uuid');
    }

    /**
     * @return HasMany
     */
    public function student_subjects(){
        return $this->hasMany('App\Models\PurchasedPackages','user_uuid','uuid');
    }

    /**
     * @return HasOne
     */
    public function student_detail(){
        return $this->hasOne('App\Models\StudentDetails','user_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function professor_subjects(){
        return $this->hasMany('App\Models\ProfessorSubjects','user_uuid','uuid');
    }

    /**
     * @return HasOne
     */
    public function professor_detail(){
        return $this->hasOne('App\Models\ProfessorDetails','user_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function moderator_daily_posts(){
        return $this->hasMany('App\Models\ModeratorDailyPost','uuid','user_uuid');
    }

    public function submitted_reviews(){
        return $this->hasMany('App\Models\Reviews','uuid','from_user_uuid');
    }

    /**
     * @param $query
     * @param $title
     * @return mixed
     */
    public function scopeOfRole($query, $title){
        return $query->whereHas('role',function ($query) use ($title){
            $query->where('title',$title);
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfVerify($query){
        return $query->where('verify',1);
    }

    /**
     * @return HasOne
     */
    public function moderator(){
        return $this->hasOne('App\Models\ModeratorSubject','user_uuid','uuid');
    }


    /**
     * @return HasMany
     */
    public function reviews(){
        return $this->hasMany('App\Models\Reviews','to_user_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function notes(){
        return $this->hasMany('App\Models\Notes','user_uuid','uuid');
    }



    /**
     * @return HasMany
     */
    public function professor_post_queries(){
        return $this->hasMany('App\Models\PostQuery','to_user_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function student_post_queries(){
        return $this->hasMany('App\Models\PostQuery','from_user_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function moderator_daily_post(){
        return $this->hasMany('App\Models\ModeratorDailyPost','user_uuid','uuid');
    }


    /**
     * @return string|null
     */
    public function getFullImagePathAttribute()
        {
            if(!empty($this->image)){
                return env('APP_URL').$this->image;
            }else{
                return null;
            }
        }
}
