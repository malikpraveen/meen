<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Help_support;
use App\Models\Support_subject;
use App\Models\User;

class QueryController extends Controller
{
    public function index(Request $request){
        if(!Auth::guard('admin')->check()){
            return redirect()->intended('admin/login');
        }
        else{
            return view('admin.query.query_list');
        }
       
       
    }

    public function Query_list(Request $request){
        $query = Help_support::with('support_subject','user')->get();
        $data['query'] = $query;
        return view('admin.query.query_list')->with($data);
    }

    public function manage_subject(Request $request){
        $subject = Support_subject::orderBy('id','asc')->get();
        $data['subject'] = $subject;
        return view('admin.query.manage_subject')->with($data);
    }

    public function queryDetail(Request $request, $id=null){
       $id = base64_decode($id);
       $query = Help_support::with('support_subject','user')->find($id);
       $data['query'] = $query;
       if($data){
        return view('admin.query.query_detail')->with($data);
    }else{
       return redirect('admin/query-management')->with('error', 'query not found');
    } 

    }

    public function subject_submit(Request $request){
        $data=[
            "subject" => $request->input('subject'),

        ];

         $submit = Support_subject::create($data);
         if($submit){
             return redirect()->back()->with('success','subject added successfully');
         }else{
             return redirect()->back()->with('error','Some error occurred while adding subject');
         }
       
        
    }

    public function change_status(Request $request){
        $id = $request->input('id');
        $status = $request->input('action');
        $update = Support_subject::find($id)->update(['status' => $status]);
        if ($update) {
            return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
        } else {
            return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
        }
    }

    public function edit_subject(Request $request, $id=null){
         $id = base64_decode($id);
         $query = Support_subject::find($id);
         $data['edit_subject'] = $query;
         return view('admin.query.edit-subject')->with($data);
    }

    public function subject_update(Request $request, $id=null){
        $id = base64_decode($id);
        $data =[
            "subject" => $request->input('subject')
        ];
        $update = Support_subject::find($id)->update($data);
        if($update){
            return redirect('admin\manage_subject')->with('success','update successfully');
        }else{
            return redirect()->back()->with('error','Some error occurred while update');
        }

    }

    public function filter(Request $request){
        $data =[
            "start_date" => $request->input('start_date'),
            "end_date" => $request->input('end_date')

        ];

        $data['query'] =  Help_support::with('support_subject','user')->whereBetween('created_at', [$data])->get();
       return view('admin.query.query_list')->with($data);
    }
    
}
