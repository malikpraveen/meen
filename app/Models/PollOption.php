<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = ['id','poll_id','option'];
    protected $primary="id";

    function userpoll(){    	
    	return $this->belongsTo(UserPoll::class,'option_id');
    }
    function users(){    	
    	return $this->hasMany(PollAnswer::class,'option_id')->with(['user_detail']);
    }

}
