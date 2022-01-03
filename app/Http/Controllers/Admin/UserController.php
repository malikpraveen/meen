<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use Response;
use Session;
use Mail;
//use App\Http\Requests\UsersRequest as StoreRequest;
//use App\Http\Requests\UsersRequest as UpdateRequest;
//use App\Http\Controllers\CrudOverrideController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPoll;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class UserController extends Controller {

    private $URI_PLACEHOLDER;
    private $jsondata;
    private $redirect;
    protected $message;
    protected $status;
    private $prefix;

    public function __construct() {
        // dd('aaaa');
        // $this->middleware('admin');
        $this->jsondata = [];
        $this->message = false;
        $this->redirect = false;
        $this->status = false;
        $this->prefix = \DB::getTablePrefix();
        $this->URI_PLACEHOLDER = \Config::get('constants.URI_PLACEHOLDER');
    }

    public function index() {
        if (!Auth::guard('admin')->check()) {
            return redirect()->intended('admin/login');
        } else {
            $users = User::orderBy('id', 'DESC')
            ->get();
             $data['users'] = $users;
             return view('admin.users.user_list')->with($data);
        }
    }

    public function change_user_status(Request $request){
       
       $id = $request->input('id');
        $status = $request->input('action');
        $update = User::find($id)->update(['status' => $status]); 
        if ($update) {
            return response()->json(['status' => true, 'error_code' => 200, 'message' => 'Status updated successfully']);
        } else {
            return response()->json(['status' => false, 'error_code' => 201, 'message' => 'Error while updating status']);
        }

    }

    public function show(Request $request, $id = null) {
        if (Auth::guard('admin')->check()) {
            $id = base64_decode($id);
            $user = User::with('user_poll')->where('id',$id)->first();   
           $data['user'] = $user;
            if ($data) {                
                
                return view('admin.users.user_detail')->with($data);
            } else {
                return redirect('admin/user-management')->with('error', 'User not found');
            }
        } else {
            return redirect()->intended('admin/login');
        }
    }
    

}
