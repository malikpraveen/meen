<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support_subject extends Model
{

    protected $table = "support_subject";
    protected $fillable = ['subject','status'];

    public function message(){
        return $this->hasOne(Help_support::class,'subject_id','id');
    }
    
}
