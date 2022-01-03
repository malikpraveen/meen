<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ringtone extends Model
{

    protected $table = "ringtone";
    protected $fillable = ['name','audio','duration_time','status'];
    
}
