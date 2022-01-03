<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $table = "contents";
    protected $fillable = ['id','name','name_ar','description','description_ar'];

    public static function fetchtremsData($data){
        return self::where('name',$data)->first();
    }

    public function getContentAttribute($value)
    {
        if(\Session::get('locale') == 'ar') {
            // dd('dd');
            return $this->description_ar;
        }
        return $value;
    }
}
