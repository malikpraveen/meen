<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
	protected $fillable = ['id','user_id','poll_id','option_id','answer'];
    function user_detail(){    	
    	return $this->belongsTo(User::class, 'user_id')->select([
        'id','first_name','last_name']);
    }
}
