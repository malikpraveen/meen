<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryDirectory extends Model
{
    protected $table="gallery_directory";
    protected $fillable = ['id','user_id','directory_name'];


    function scopeTotalSize($query, $id){
    	$total_size=\DB::table('directory_files')->where('directory_id',$id)->sum('file_size');
    	return $total_size;
    }

    function scopeTotalFiles($query, $id){
    	$total_files=\DB::table('directory_files')->where('directory_id',$id)->count();
    	return $total_files;
    }

}
