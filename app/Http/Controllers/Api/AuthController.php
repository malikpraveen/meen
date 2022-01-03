<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Log;
use App\Models\User;
use App\Models\Admin;
use App\Models\Otp;
use App\Models\Subscription;
use App\Models\GalleryDirectory;
use App\Models\UserPoll;
use App\Models\PollOption;
use App\Models\PollAnswer;
use App\Models\UserEvent;
use App\Models\EventReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\sendmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name'          =>  'required|max:40',
            'last_name'          =>  'required|max:40',
            'email'         =>  'email',
            'mobile_number' =>  'integer|min:8',
            'password' => [
                'required',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            //'type'        =>  'required',
        ],[
            'first_name.required'   =>  trans('messages.F001'),
            'last_name.required'   =>  trans('messages.F001'),
            'first_name.max'   =>  trans('messages.F027'),
            'last_name.max'   =>  trans('messages.F027'),
            'email.email'        =>  trans('messages.F003'),
            'mobile_number.integers'          =>  trans('messages.F028'),
            'mobile_number.min'          =>  trans('messages.F030'),
            'password.required'          =>  trans('messages.F005'),
            'password.min'          =>  trans('messages.F029'),
            'password.regex'          =>  trans('messages.F031'),
            //'type.required'          =>  trans('messages.F034'),
        ]);

        $validator->after(function($validator) use($request) {

            $mobile_number = User::where('country_code',$request['country_code'])->where('mobile_number',$request['mobile_number'])->whereNotIn('status',['trashed'])->first();
            if ($mobile_number) {
                $validator->errors()->add('mobile_number', trans('messages.F033'));
            }
            if($request['email']){
                $email = User::where('email',$request['email'])->whereNotIn('status',['trashed'])->first();
                if ($email) {
                    $validator->errors()->add('email', trans('messages.F032'));
                }
            }
            
            
        });

        if ($validator->fails()) {
            // dd($validator->errors());
            $this->message = $validator->errors();
        }
        else
        {

            $addUser['password']        =   bcrypt($request->password);
            $addUser['first_name']            =   $request['first_name'];
            $addUser['last_name']            =   $request['last_name'];
            $addUser['email']           =   $request['email'];
            $addUser['is_otp_verified'] =   'no';
            $addUser['status']          =   'inactive';
            $addUser['type']            =   'user';
            $addUser['country_code']    =   $request['country_code'];
            $addUser['mobile_number']   =   $request['mobile_number'];
            // $addUser['type']            =   $request['type'];
            //dd($addUser);
            $data                       =   User::create($addUser);
            //dd($data);
            $otpUser['otp']             =   '1111';
            $otpUser['user_id']         =   $data['id'];
            $otp                        =   Otp::create($otpUser);

            $response = new \Lib\PopulateResponse($data);

            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = trans('messages.F007');
        }
            return $this->populateResponse();
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            //'country_code' => ['required'],
            //'mobile_number' => ['required'],
            'password' =>['required']
        ],[
            //'mobile_number.required'        =>  trans('messages.F022'),
            'password.required'             =>  trans('messages.F005'),
            //'country_code.required'         =>  trans('messages.F023'),
        ]);
        
        if(!empty($request->mobile_number)){
            $validator->after(function($validator) use(&$user, $request) {
                $credentials = request(['country_code','mobile_number', 'password']);

                if(!Auth::attempt($credentials))
                    $validator->errors()->add('mobile_number', trans('messages.F011'));

                
            });
        }
        if(!empty($request->email)){
            $validator->after(function($validator) use(&$user, $request) {
                $credentials = request(['email', 'password']);

                if(!Auth::attempt($credentials))
                    $validator->errors()->add('email', trans('messages.F011'));

                
            });
        }
        
        
        if ($validator->fails()) {
           $this->message = $validator->errors();
           //dd('hi');
        }else{
            if(!empty($request->mobile_number)){
                $userInactive = User::where('mobile_number', $request->mobile_number)->where('country_code', $request->country_code)->orderBy('created_at', 'desc')/*->where('is_completed','yes')*/->first();
            }
            if(!empty($request->email)){
                $userInactive = User::where('email', $request->email)->orderBy('created_at', 'desc')/*->where('is_completed','yes')*/->first();
            }
            
            if($userInactive['status'] == 'inactive'){

                $otpUser['otp']            =   '1
                111';
                $otpUser['user_id']         =   $userInactive['id'];
                $otp                        =   Otp::create($otpUser);

                $this->status   = true;
                $response = new \Lib\PopulateResponse($userInactive);

                $this->data = $response->apiResponse();
                return $this->populateResponse();

            }
            // dd('dd');
            if(!empty($request->mobile_number)){
                $user = User::where([
                    'country_code'=>$request->country_code,
                    'mobile_number'=>$request->mobile_number,
                    'status'=>'active'
                ])->first();
            }
            if(!empty($request->email)){
                $user = User::where([
                    'email'=>$request->email,
                    'status'=>'active'
                ])->first();
            }    

            $updateArr = array();
            // $updateArr['timezone'] = $request->timezone;
            if($request->device_token != "" && $request->device_type != "") {
                $updateArr['device_token'] = $request->device_token;
                $updateArr['device_type'] = $request->device_type == 'iphone' ? 'ios' : 'android';
            }
            if ($updateArr) {
                User::where('id',$user->id)->update($updateArr);
            }

            $userTokens = $user->tokens;

            if($userTokens){
                foreach($userTokens as $token) {
                    $token->revoke();   
                }
            }

            $tokenResult =  $user->createToken('MyApp');
            $token = $tokenResult->token;
            $token->save();
            $user['token'] = $tokenResult->accessToken;
            
            $response = new \Lib\PopulateResponse($user);

            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = trans('messages.F012');
            
        }
        return $this->populateResponse();
    }

    public function forgotPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile_number' => 'required',
        ],[
            'country_code.required' => trans('messages.F023'),
            'mobile_number.required' => trans('messages.F022'),
        ]);


        $validator->after(function($validator) use($request) {

            $user = User::where('country_code', $request->country_code)->where('mobile_number', $request->mobile_number)->first();

            if(empty($user)){
                $validator->errors()->add('mobile_number', trans('messages.F024'));
            }else{
                if($user->status == 'inactive'){
                    $validator->errors()->add('mobile_number', trans('messages.F025'));
                }
                if($user->status == 'trashed'){
                    $validator->errors()->add('mobile_number', trans('messages.F026'));
                }

            }

        });
        
        if ($validator->fails()) {
            // $this->type  = 'first';

            $this->message = $validator->errors();
        }
        else {
            $user = User::where('country_code', $request->country_code)->where('mobile_number', $request->mobile_number)->first();
            $otpUser['user_id'] = $user['id'];
            $otpUser['otp']     = '1111';

            $otp                        =   Otp::create($otpUser);

            $response = new \Lib\PopulateResponse($otpUser);
            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = 'Otp send successfully.';
        }
        
        return $this->populateResponse();     

    }

    public function requestOtpadmin(Request $request)
    {
   
           $otp = rand(1000,9999);
            Log::info("otp = ".$otp);
           $admin = Admin::where('email','=',$request->email)->where('name','admin')->first();
          
           $otpAdmin['otp'] = $otp;
           $otpAdmin['user_id'] = $admin['id'];
           $otpcreate = otp::create($otpAdmin);
           //return ["result"=>$otpcreate];
           

   
           if($admin){
           // send otp in the email
          $details = [
               'subject' => 'Testing Application OTP',
               'body' => 'Your OTP is : '. $otp
           ];
        
            \Mail::to($request->email)->send(new sendmail($details));
          
            return response(["status" => 200, "message" => "OTP sent successfully"]);
           }
           else{
               return response(["status" => 401, 'message' => 'Invalid']);
           }
       }
   

    public function otp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'otp'         =>  'required',
            'user_id'     =>  'required'
        ],[
            'otp.required'   =>  trans('messages.F008'),
            'user.required'   =>  trans('messages.F008'),
        ]);

        $validator->after(function($validator) use($request) {
            $checkOTP = OTP::where([
                'user_id' => $request['user_id'],
                'otp' => $request['otp'],
            ])->latest()->first();
            // dd($checkOTP);
            if(empty($checkOTP)){
                $validator->errors()->add('error', trans('messages.F009'));
            }
            
        });

        if ($validator->fails()) {
            // $this->type  = 'first';

            $this->message = $validator->errors();
        }
        else
        {
            // dd('dd');
            $user = User::find($request['user_id']);
            // if($request->type == 'registration'){
            User::where('id', $request['user_id'])->update([
                'is_otp_verified' => 'yes',
                'status' => 'active',
            ]);
            $userTokens = $user->tokens;

            if($userTokens){
                foreach($userTokens as $token) {
                    $token->revoke();   
                }
            }

            $tokenResult =  $user->createToken('MyApp');
            $token = $tokenResult->token;
            $token->save();
            $user['token'] = $tokenResult->accessToken;
            //     $userTokens = $user->tokens;

            //     foreach($userTokens as $token) {
            //         $token->revoke();   
            //     }
            
            //     $tokenResult =  $user->createToken('MyApp');
            //     $token = $tokenResult->token;
            //     $token->save();
            //     $data = User::with('children','country')->where('id', $request['user_id'])->first();
            //     // dd($data);
            //     $data['token'] = $tokenResult->accessToken;
            //     $response = new \Lib\PopulateResponse($data);

            //     $this->data = $response->apiResponse();
            //     $this->status   = true;
            //     $this->message = trans('messages.F010');
            // }elseif ($request->type == 'forgotPassword') {
            $data = [];
            $users = User::find($request['user_id']);
            
            $response = new \Lib\PopulateResponse($user);

            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message = trans('messages.F010');
            // }
        }
            return $this->populateResponse();
        
    }

    public function resendOTP(Request $request)
    {
        $user = User::find($request->user_id);
        if($user){
            // OTP::where('user_id', $request->id)->latest()->first();

            // $data                       =   User::create($addUser);
            $otpUser['otp']             =   '1111';
            $otpUser['user_id']         =   $request->user_id;
            $otp                        =   OTP::create($otpUser);

            // $emailData['base_url']      = url('');
            // $emailData['site']          = 'ARABIC';
            // $emailData['email']         = $user['email'];
            // $emailData['name']          = ucfirst($user['name']);
            // // $emailData['userid']        = encrypt($user['id']);
            // $emailData['otp']           = $otpUser['otp'];
            // $statius                    = ___mail_sender($user['email'], $emailData['name'],'register',$emailData);
            // dd($statius);
            $data = [];
            $response = new \Lib\PopulateResponse($data);

            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = 'OTP resend successfully.';
        }
        return $this->populateResponse(); 
    }

    public function updatePassword(Request $request)
    {
        // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'new_password'              =>  'required|min:6|max:15',
            'confirm_password'          =>  'required|same:new_password',
            
        ],[
            'new_password.required'     =>  trans('messages.F016'),
            'new_password.min'          =>  trans('messages.F017'),
            'new_password.max'          =>  trans('messages.F017'),
            'confirm_password.required'        =>  trans('messages.F018'),
            'confirm_password.same'        =>  trans('messages.F019'),
            
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            // $this->type  = 'first';

            $this->message = $validator->errors();
        }else{
            $input['password']  = bcrypt($request->new_password);
            // dd($request->user_id);
            User::where('id','=',$request->user_id)->update($input);
            $data = [];
            $response = new \Lib\PopulateResponse($data);
            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = trans('messages.F020');
        }
        // dd("ccc");

        return $this->populateResponse();  
    }

    public function planListing(Request $request)
    {

        $data['user'] = User::find(Auth::guard('api')->id());

        $plans = Subscription::where('status','active')->get();

        $response = new \Lib\PopulateResponse($plans);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Plans fetched successfully.';
            return $this->populateResponse(); 
    }


    public function addFolder(Request $request){
        $validator = \Validator::make($request->all(), [
            'directory_name'              =>  'required',
            
        ],[
            'directory_name.required'     =>  trans('messages.F044'),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $insert=[
                'user_id'=>Auth::guard('api')->id(),
                'directory_name'=>$request->directory_name
            ];
            $add=GalleryDirectory::create($insert);
            if($add){
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = trans('messages.F042');
            }else{
                $this->status   = true;
                $this->message  = trans('messages.F043');
            }
        }
        // dd("ccc");

        return $this->populateResponse(); 
    }

    public function myFolders()
    {
        $directory_list=[];
        $directory = GalleryDirectory::select('*')->where('user_id',Auth::guard('api')->id())->where('status','1')->get();
        // ->totalSize('gallery_directory.id');
        if($directory){
            foreach ($directory as $key => $value) {
                $value->total_size=0;
                $value->total_files=0;
                array_push($directory_list,$value);
            }
        }
        // print_r($directory);die;
        $data['directory']=$directory_list;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Directories fetched successfully.';
            return $this->populateResponse(); 
    }

    public function addPoll(Request $request){
        $validator = \Validator::make($request->all(), [
            'question' =>  'required',
            'time' =>  'required|integer',
            
        ],[
            'question.required'     =>  trans("validation.required",['attribute'=>'Question']),
            'time.required'     =>  trans("validation.required",['attribute'=>'Time']),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $insert=[
                'user_id'=>Auth::guard('api')->id(),
                'question'=>$request->question,
                'type'=>'mcq',
                'time'=>$request->time
            ];
            $add=UserPoll::create($insert);
            if($add){
                if($request->options){
                    foreach($request->options as $option){
                        $insert=[
                            'poll_id'=>$add['id'],
                            'option'=>$option
                        ];
                        $addOption=PollOption::create($insert);
                    }
                }
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Poll question added successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while adding poll";
            }
        }
        // dd("ccc");

        return $this->populateResponse(); 
    }

    public function myPolls()
    {
        $polls = UserPoll::select('*')->with('created_by','options')->where('user_id',Auth::guard('api')->id())->where('status','1')->get();

        $data['polls']=$polls;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Polls fetched successfully.';
            return $this->populateResponse(); 
    }

    public function allPolls()
    {
        $polls = UserPoll::select('*')->with('created_by','options')
        // ->where('user_id','<>',Auth::guard('api')->id())
        ->where('status','1')->get();

        $data['polls']=$polls;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Polls fetched successfully.';
            return $this->populateResponse(); 
    }


    public function sendMyAnswer(Request $request){
        $validator = \Validator::make($request->all(), [
            'poll_id' =>  'required',
            'option_id' =>  'required|integer',
            
        ],[
            'poll_id.required'     =>  trans("validation.required",['attribute'=>'poll_id']),
            'option_id.required'     =>  trans("validation.required",['attribute'=>'option']),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            PollAnswer::where(['user_id'=>Auth::guard('api')->id(),'poll_id'=>$request->poll_id])->delete();
            $insert=[
                'user_id'=>Auth::guard('api')->id(),
                'poll_id'=>$request->poll_id,
                'option_id'=>$request->option_id
            ];
            // print_r($insert);die;
            $add=PollAnswer::create($insert);
            if($add){
                $allAnswer=PollAnswer::where(['poll_id'=>$request->poll_id])->count();
                $pollOptions=PollOption::where(['poll_id'=>$request->poll_id])->get();
                if($pollOptions){
                    foreach($pollOptions as $pollOption){
                        $selectedOption=PollAnswer::where(['poll_id'=>$request->poll_id,'option_id'=>$pollOption->id])->count();
                        $percentage=($selectedOption/$allAnswer)*100;
                        PollOption::where(['poll_id'=>$request->poll_id,'id'=>$pollOption->id])->update(['selection_percentage'=>$percentage]);
                    }
                }
                
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Your answer sent successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while sending your response";
            }
        }
        // dd("ccc");

        return $this->populateResponse();
    }


    public function deletePoll(Request $request){
        $validator = \Validator::make($request->all(), [
            'poll_id' =>  'required'
            
        ],[
            'poll_id.required'     =>  trans("validation.required",['attribute'=>'poll_id'])
        ]);

        $validator->after(function($validator) use($request) {
            if($request->poll_id){
                if(!UserPoll::where(['id'=>$request->poll_id,'user_id'->Auth::guard('api')->id()])->first()){
                    $validator->errors()->add('poll_id', trans('messages.F045'));
                }
            }
            
        });
        $add=UserPoll::where(['id'=>$request->poll_id])->update(['status'=>"0"]);
            if($add){
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Your poll deleted successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while deleting poll";
            }

        return $this->populateResponse();
    }

    public function addEvent(Request $request){
        $validator = \Validator::make($request->all(), [
            'title' =>  'required',
            'description' =>  'required',
            'image' =>  'required',
            'date' =>  'required',
            'time' =>  'required',
        ],[
            'title.required'     =>  trans("validation.required",['attribute'=>'Title']),
            'description.required'     =>  trans("validation.required",['attribute'=>'Description']),
            'image.required'     =>  trans("validation.required",['attribute'=>'Image']),
            'date.required'     =>  trans("validation.required",['attribute'=>'Date']),
            'time.required'     =>  trans("validation.required",['attribute'=>'Time']),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $insert=[
                'user_id'=>Auth::guard('api')->id(),
                'title'=>$request->title,
                'description'=>$request->description,
                // 'image'=>$request->image,
                'date'=>$request->date,
                'time'=>$request->time
            ];
            if($request->image){
                $filename = $request->image->getClientOriginalName();
                $imageName = time().'.'.$filename;
                if(env('APP_ENV') == 'local'){
                    $return = $request->image->move(
                    base_path() . '/public/uploads/events/', $imageName);
                }else{
                    $return = $request->image->move(
                    base_path() . '/../public/uploads/events/', $imageName);
                }
                $url = url('/uploads/events/');
                $insert['image'] = $url.'/'.$imageName;
            }
            if($request->reminder){
                $insert['is_reminder']="1";
            }
            $add=UserEvent::create($insert);
            if($add){
                if($request->reminder){
                    foreach($request->reminder as $reminder){
                        $insert=[
                            'event_id'=>$add['id'],
                            'reminder_time'=>$reminder,
                            'set_time'=>$reminder
                        ];
                        $addOption=EventReminder::create($insert);
                    }
                }
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Event added successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while adding poll";
            }
        }
        // dd("ccc");

        return $this->populateResponse(); 
    }

    public function myEventList()
    {
        $events = UserEvent::select('*')->where('user_id',Auth::guard('api')->id())->where('status','1')->get();

        $data['events']=$events;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Events fetched successfully.';
            return $this->populateResponse(); 
    }

    public function allEventList()
    {
        $events = UserEvent::select('*')
        // ->where('user_id','<>',Auth::guard('api')->id())
        ->where('status','1')->get();

        $data['events']=$events;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Events fetched successfully.';
            return $this->populateResponse(); 
    }
}
