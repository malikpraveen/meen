<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help_support extends Model
{

    protected $table = "help_support";
    protected $fillable = ['user_id','subject_id','message','status'];

    public function support_subject(){
        return $this->belongsTo(Support_subject::class,'subject_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
