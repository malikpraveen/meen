<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class assignContactGroup extends Model
{
    protected $table = "assign_contact_group";
    protected $fillable = ['id','user_id','contact_user_id','group_name'];
}
