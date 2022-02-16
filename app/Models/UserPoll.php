<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoll extends Model
{
    protected $table="user_polls";
	protected $fillable = ['id','user_id','question','type','time','status'];
    
    function options(){    	
    	return $this->hasMany(PollOption::class,'poll_id','id');
    }
    function started_by(){    	
    	return $this->belongsTo(User::class, 'user_id','id')->select([
        'id','first_name','last_name','email','mobile_number','user_name','profile_pic']);
    }
    function created_by(){      
        return $this->belongsTo(User::class, 'user_id')->select([
        'id','first_name','last_name','email','country_code','mobile_number','profile_pic']);
    }
   
}
