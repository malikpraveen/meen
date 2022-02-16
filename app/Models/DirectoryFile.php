<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectoryFile extends Model
{
    protected $table = "directory_files";
    protected $fillable = ['id','user_id','directory_id','file_path','file_name','file_size','file_type','status'];


}

