<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable ;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasApiTokens, Notifiable;
    //
    protected $table = "users";
    protected $fillable = ['first_name','last_name','user_name','password','email','device_token', 'device_type','remember_token','is_otp_verified', 'status','type','mobile_number','country_code','profile_pic'];

    protected $hidden=["password"];

    public function user_poll(){
        return $this->hasMany(UserPoll::class)->with('options');
    }

    public function user_message(){
        return $this->hasOne(Help_support::class,'user_id','id');
    }

    public function event(){
        return $this->belongsTo(UserEvent::class,'id','user_id');
    }
}
