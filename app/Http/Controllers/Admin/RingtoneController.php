<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Ringtone;
use DB;
use Carbon\Carbon;
use DateTime;
use App\File;
use wapmorgan\Mp3Info\Mp3Info;

class RingtoneController extends Controller
{


    public function index(Request $request){
        if(!Auth::guard('admin')->check()){
            return redirect()->intended('admin/login');
        }
        else{
            return view('admin.ringtone.ringtone_list');
        }
       
       
    }


    public function Ringtone_list(Request $request){

        $data['ringtone'] = Ringtone::orderBy('id','asc')->get(); 
        return view('admin.ringtone.ringtone_list')->with($data);
    }

    public function ringtone_submit(Request $request){
        $duration_time = $request->file('audio');
        $size=$duration_time->getSize();
        $duration_time1 = gmdate("H:i:s", round(($size/24212.925)));
        
        $data=[
            'name' => $request->input('name'),
           'duration_time' =>  $duration_time1
        ];
        if ($request->hasFile('audio')) {
            $music_file = $request->file('audio');
            // $getID3 = new \getID3;
            // $ThisFileInfo = $getID3->analyze($music_file);
            // $len= @$ThisFileInfo['playtime_string']; 
            // return $len;
            $size=$music_file->getSize();
            $extention = $music_file->getClientOriginalExtension();
            $file_name = $music_file->getClientOriginalName();
            $public_path = public_path();
            $location = $public_path . "/upload/audio_directory/" ;
            $audio = $music_file->move($location, $file_name,$extention);
        
    
            $data['audio'] = url('/upload/audio_directory/' . $file_name );
        }
      
        $add = Ringtone::create($data);
       
        if ($add) {
           return redirect()->back()->with('success','ringtone added successfully');
        } else {
            return redirect()->back()->with('error', 'Some error occurred while adding subscription');
        }
    }


    public function change_status(Request $request){
        $id = $request->input('id');
        $status = $request->input('action');
        $update = Ringtone::find($id)->update(['status' => $status]);
        if ($update) {
            return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
        } else {
            return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
        }


    }

    public function delete_ringtone(Request $request){
        $id = $request->input('id');
        $delete_ringtone = Ringtone::find($id);
        $delete = $delete_ringtone->delete();
        if($delete){
            return response()->json(['success'=>true, 'error_code'=>200, 'message'=>'ringtone delete successfully']);
         }else{
            return response()->json(['success'=>false, 'error_code'=>201, 'message'=>'error while deleteing ringtone']);
         }
    }

    public function edit_ringtone(Request $request, $id=null){
        $id = base64_decode($id);
        $query = Ringtone::where('id',$id)->first();
        $data['edit_ringtone'] = $query;
        if($data){
            return view('admin.ringtone.edit-ringtone')->with($data);
        }else{
            return redirect()->back()->with('error','details not found');
        }
    }

    public function ringtone_update(Request $request, $id=null){
        $id = base64_decode($id);
          $data= [
            "name" => $request->input('name'),
            'duration_time' => date(' H:i:s')
           
        ];
        if ($request->hasFile('audio')) {
            $music_file = $request->file('audio');
            $size=$music_file->getSize();
            $extention = $music_file->getClientOriginalExtension();
            $file_name = $music_file->getClientOriginalName();
            $public_path = public_path();
            $location = $public_path . "/upload/audio_directory/" ;
            $audio = $music_file->move($location, $file_name,$extention);
        
    
            $data['audio'] = url('/upload/audio_directory/' . $file_name );
        }
    
       $update = Ringtone::find($id)->update($data);
       if($update){
        return redirect('admin/ringtone-management')->with('success', ' update successfully.');
    }
    else {
        return redirect()->back()->with('error', 'Some error occurred while update ');
    }

    }

}
