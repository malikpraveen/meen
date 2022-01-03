<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $table = "subscriptions";
    protected $fillable = ['name', 'validity','storage_validity','size_kb','size_gb' ,'amount','status'];

   
}
