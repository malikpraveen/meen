<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
   protected $table = "user_events";
   protected $fillable = ['title','description','image','date','time','end_time','is_reminder','status'];

   public function user(){
     return $this->hasMany('App\Models\User','id','user_id');
   }

 
}
