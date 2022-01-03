<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserEvent;
use App\Models\User;
use DB;
use Carbon\Carbon;
use DateTime;

class EventController extends Controller
{
    public function event_list(Request $request){
    
          $current_date_time = Carbon::now()->toDateTimeString();
    
       //return $current->toTimeString();
      // return $current->toDateTimeString();
        // $ldate = date('Y-m-d H:i:s');
         $ltime = date('H:i:s');

         $upcoming_event = DB::table('user_events') 
         ->leftjoin('users','user_events.user_id','=','users.id')
         ->select('user_events.*','user_name')
         ->where('user_events.date', '>', date("Y-m-d"))
         ->orwhere(function ($query) use($ltime) {
            $query->where('user_events.date', '=',date("Y-m-d"))
             ->where('user_events.time', '>',$ltime);
           })
        ->get();
       $data['upcoming_event'] = $upcoming_event; 



        $ongoing_event = DB::table('user_events')
        ->leftjoin('users','user_events.user_id','=','users.id')
        ->select('user_events.*','user_name')
        ->where('user_events.date','=', date("Y-m-d"))
        ->where(function ($query) use($ltime) {
            $query->where('user_events.time', '=',date("H:i:s"))
             ->orwhere('user_events.end_time', '>',$ltime);
           })
        ->get();
        $data['ongoing_event'] = $ongoing_event;

        //return $data;


        $over_due_event = DB::table('user_events')
        ->leftjoin('users','user_events.user_id','=','users.id')
        ->select('user_events.*','user_name')
        ->where('user_events.date','<', date("Y-m-d"))
        ->orwhere(function ($query) use($ltime) {
            $query->where('user_events.date', '=',date("Y-m-d"))
             ->where('user_events.end_time', '<',$ltime);
           })
        ->get();
        $data['over_due_event'] = $over_due_event;
        //return $data;

       return view('admin.event.event-list')->with($data);

    }

    public function event_detail(Request $request, $id = null){
        $id = base64_decode($id);
         $event_detail = UserEvent::find($id);
         $user_data = User::select('user_name','email','mobile_number','profile_pic')->where('id',$event_detail->user_id)->get();
         //return $user_data;
         $data['eventdetail'] = $event_detail;
         $data['userdata'] = $user_data;
         //return $data;
         if($data){
             return view('admin.event.event-detail')->with($data);
         }else{
            return redirect('admin/event-management')->with('error', 'event not found');
         }

        

    }

    public function event_delete(Request $request ){
           $id = $request->input('id');
             $event_delete = UserEvent::find($id);
            $delete = $event_delete->delete();
            if ($delete) {
              return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Event deleted successfully']);
          } else {
              return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while deleting event']);
          }
            //  if ($delete) {
            //     return redirect('admin/event-management')->with('success', 'event delete successfully.');
            // } else {
            //     return redirect()->back()->with('error', 'Some error occurred while delete event');
            // }
            

    }

    public function change_status(Request $request){
      $id = $request->input('id');
      $status = $request->input('action');
      $update = UserEvent::find($id)->update(['status'=>$status]);
      if ($update) {
        return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
    } else {
        return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
    }
    }
}
