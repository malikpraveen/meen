<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserPoll;

class PollController extends Controller
{
   public function poll_list(Request $request){
    
       $poll_list = UserPoll::with('started_by')->get();
       $data['poll_list'] = $poll_list;
       //dd($data);
       //return $data;
       return view('admin.poll.poll-list')->with($data);

   }

   public function poll_detail(Request $request, $id=null){
         $id = base64_decode($id);
         $poll_detail = UserPoll::with('started_by', 'options')->find($id);
         $data['poll_detail'] = $poll_detail;
         //$data['option'] = $option;


         if($data){
            return view('admin.poll.poll-detail')->with($data);
        }else{
           return redirect('admin/poll-management')->with('error', 'event not found');
        }    

   }

   public function poll_delete(Request $request){
        $id = $request->input('id');
        $delete_poll = UserPoll::find($id);
        $delete = $delete_poll->delete();
        if($delete){
           return response()->json(['success'=>true, 'error_code'=>200, 'message'=>'Poll delete successfully']);
        }else{
           return response()->json(['success'=>false, 'error_code'=>201, 'message'=>'error while deleteing poll']);
        }


     }

     public function change_status(Request $request){
        $id = $request->input('id');
        $status = $request->input('action');
        $update = UserPoll::find($id)->update(['status'=>$status]);
        if ($update) {
         return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
     } else {
         return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
     }
     }

}
