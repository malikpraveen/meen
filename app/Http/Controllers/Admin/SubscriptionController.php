<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use DB;

class SubscriptionController extends Controller
{
    public function index(Request $request){
        if(!Auth::guard('admin')->check()){
            return redirect()->intended('admin/login');
        }
        else{
            return view('admin.subscription.subscription_list');
        }
       
       
    }

    public function submit(Request $request){
       $size = $request->input('size');
       $kb_size = round($size*1048576);
       
        //$data = $request->all();
         $data=[
            "name" => $request->input('name'),
            "validity" => $request->input('validity'),
            "storage_validity" => $request->input('storage_validity'),
            "size_kb" => $kb_size,
            "size_gb" => $request->input('size'),
            "amount" => $request->input('amount'),

        ];
         $add = Subscription::create($data);
       
        if ($add) {
           return redirect()->back()->with('success','subscription added successfully');
        } else {
            return redirect()->back()->with('error', 'Some error occurred while adding subscription');
        }
        
    }

    public function Subscription_list(Request $request){
 
//$data['subscription_data'] = Subscription::select('*')->selectRaw('(ROUND((size_kb  / 1048576))) AS size')->orderBy('id','asc')->get();
       $data['subscription_data'] = Subscription::orderBy('id','asc')->get();
          
        return view('admin.subscription.subscription_list')->with($data);
    }

    public function change_status(Request $request){
        $id = $request->input('id');
        $status = $request->input('action');
        $update = Subscription::find($id)->update(['status' => $status]);
        if ($update) {
            return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
        } else {
            return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
        }


    }

    public function edit_subscription(Request $request, $id=null){
        $id = base64_decode($id);
        // return $id;
        $data['edit_subscription'] = Subscription::where('id',$id)->first();
        if($data){
            return view('admin.subscription.edit_subscription')->with($data);

        }else{
            return redirect()->back()->with('error','details not found');
        }
       
        
    }

    public function edit_update(Request $request, $id=null){
         $id = base64_decode($id);
         $size = $request->input('size');
         $kb_size = round($size*1048576);
         
           $data=[
              "name" => $request->input('name'),
              "validity" => $request->input('validity'),
              "storage_validity" => $request->input('storage_validity'),
              "size_kb" => $kb_size,
              "size_gb" => $request->input('size'),
              "amount" => $request->input('amount'),
  
          ];
   

    $update = Subscription::find($id)->update($data);
    if($update){
        return redirect('admin/subscription-management')->with('success', ' update successfully.');
    }
    else {
        return redirect()->back()->with('error', 'Some error occurred while update ');
    }

    }
}
