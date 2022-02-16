<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use App\Models\Otp;
use App\Models\Ringtone;
use App\Models\Subscription;
use App\Models\GalleryDirectory;
use App\Models\UserPoll;
use App\Models\PollOption;
use App\Models\PollAnswer;
use App\Models\UserEvent;
use App\Models\assignContactGroup;
use App\Models\EventReminder;
use App\Models\DirectoryFile;
use App\Models\ScheduleMessageSend;
use App\Models\ScheduleMessageDelete;
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
            'user_name'          =>  'required|max:40',
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
            'user_name.required'   =>  trans('messages.F097'),
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
            if($request['mobile_number']){
                $mobile_number = User::where('country_code',$request['country_code'])->where('mobile_number',$request['mobile_number'])->whereNotIn('status',['trashed'])->first();
                if ($mobile_number) {
                    $validator->errors()->add('mobile_number', trans('messages.F033'));
                }
            }
            if($request['email']){
                $email = User::where('email',$request['email'])->whereNotIn('status',['trashed'])->first();
                if ($email) {
                    $validator->errors()->add('email', trans('messages.F032'));
                }
            }
            if($request['user_name']){
                $username = User::where('user_name',$request['user_name'])->whereNotIn('status',['trashed'])->first();
                if ($username) {
                    $validator->errors()->add('user_name', trans('messages.F097'));
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
            $addUser['user_name']            =   $request['user_name'];
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
                
                $mobile_number = User::where('country_code',$request['country_code'])->where('mobile_number',$request['mobile_number'])->whereNotIn('status',['trashed'])->first();
                if ($mobile_number) {
                    $credentials = request(['country_code','mobile_number', 'password']);

                    if(!Auth::attempt($credentials))
                    $validator->errors()->add('mobile_number', trans('messages.F011'));
                }else{
                    $validator->errors()->add('mobile_number', trans('messages.F099'));
                }
                
            });
        }
        if(!empty($request->email)){
            $validator->after(function($validator) use(&$user, $request) {
                $email = User::where('email',$request['email'])->whereNotIn('status',['trashed'])->first();
                if ($email) {
                    $credentials = request(['email', 'password']);

                    if(!Auth::attempt($credentials))
                    $validator->errors()->add('email', trans('messages.F011'));
                }else{
                    $validator->errors()->add('email', trans('messages.F098'));
                }
                
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
            if($request->email){
                $user = User::where('email', $request->email)->first();
            }else{
                $user = User::where('country_code', $request->country_code)->where('mobile_number', $request->mobile_number)->first();

            }
            
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
            if($request->email){
                $user = User::where('email', $request->email)->first();
            }else{
                $user = User::where('country_code', $request->country_code)->where('mobile_number', $request->mobile_number)->first();
            }
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
            // 'new_password'              =>  'required|min:8|max:15',
            'new_password'              =>[
                'required',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password'          =>  'required|same:new_password',
            
        ],[
            // 'new_password.required'     =>  trans('messages.F016'),
            // 'new_password.min'          =>  trans('messages.F017'),
            // 'new_password.max'          =>  trans('messages.F017'),
            'new_password.required'        =>  trans('messages.F005'),
            'new_password.min'             =>  trans('messages.F029'),
            'new_password.regex'           =>  trans('messages.F031'),
            'confirm_password.required'    =>  trans('messages.F018'),
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
        $total_files = 0;
        $total_size = 0;
         $directory = GalleryDirectory::select('*')->where('user_id',34)->where('status','1')->get();
       
        if($directory){
            foreach ($directory as $key => $value) {

                 $total_files =  DirectoryFile::where('directory_id',$value->id)->count();
                 $total_size =  DirectoryFile::where('directory_id',$value->id)->sum('file_size')/(1024); //in KB

                $value->total_size=$total_size ;
                $value->total_files=$total_files;
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
            'time' =>  'required',
            
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
                    if(is_array($request->options)){
                        $options= $request->options;
                    }else{
                        $options=json_decode($request->options);
                        // if (json_last_error() === 0) {
                            // JSON is valid
                        // }
                    }
                    if($options){
                        foreach($options as $option){
                            if($option){
                                $insert=[
                                    'poll_id'=>$add['id'],
                                    'option'=>$option
                                ];
                                $addOption=PollOption::create($insert);
                            }
                        }
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
        $polls = UserPoll::select('*')->with('created_by','options')->where('user_id',Auth::guard('api')->id())->where('status','1')->orderBy('id','DESC')->get();

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
        ->where('status','1')->orderBy('id','DESC')->get();

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
            // 'image' =>  'required',
            // 'image' =>  'image|mimes:jpeg,jpg,png',
            'date' =>  'required',
            'time' =>  'required',
            'end_time' =>  'required',
            'location' =>  'required',
        ],[
            'title.required'     =>  trans("validation.required",['attribute'=>'Title']),
            'description.required'     =>  trans("validation.required",['attribute'=>'Description']),
            // 'image.required'     =>  trans("validation.required",['attribute'=>'Image']),
            'date.required'     =>  trans("validation.required",['attribute'=>'Date']),
            'time.required'     =>  trans("validation.required",['attribute'=>'Start Time']),
            'end_time.required'     =>  trans("validation.required",['attribute'=>'End Time']),
            'location.required'     =>  trans("validation.required",['attribute'=>'Location']),
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
                'time'=>$request->time,
                'end_time'=>$request->end_time,
                'location'=>$request->location
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
                // $insert['image'] = $url;
            }
            if($request->reminder){
                $insert['is_reminder']="1";
            }
            $add=UserEvent::create($insert);
            if($add){
               
                if($request->reminder){
                    if(is_array($request->reminder)){
                        $reminders=$request->reminder;
                    }else{
                        $reminders=json_decode($request->reminder);
                    }
                    if($reminders){
                        foreach($reminders as $reminder){
                            if($reminder){
                                $insert=[
                                    'event_id'=>$add['id'],
                                    'reminder_time'=>$reminder,
                                    'set_time'=>$reminder
                                ];
                                $addOption=EventReminder::create($insert);
                            }
                        }
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
        $events = UserEvent::select('*')->where('user_id',Auth::guard('api')->id())
         ->where('status','1')
        ->orderBy('id','DESC')
        ->get();
        
        if(!empty($events)){
            foreach($events as $event){
                $event->time=date('h:i A',strtotime($event->time));
                $event->end_time=date('h:i A',strtotime($event->end_time));
                $event->reminder=EventReminder::where('event_id',$event->id)->get();
            }
        }

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
         ->where('status','1')
        ->orderBy('id','DESC')
        ->get();
        if(!empty($events)){
            foreach($events as $event){
                $event->time=date('h:i A',strtotime($event->time));
                $event->end_time=date('h:i A',strtotime($event->end_time));
                $event->reminder=EventReminder::where('event_id',$event->id)->get();
            }
        }
        $data['events']=$events;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Events fetched successfully.';
            return $this->populateResponse(); 
    }
    
    public function updateEvent(Request $request){
        $validator = \Validator::make($request->all(), [
            'event_id' =>  'required',
            'title' =>  'required',
            'description' =>  'required',
            'image' =>  'required',
            // 'image' =>  'image|mimes:jpeg,jpg,png',
            'date' =>  'required',
            'time' =>  'required',
            'end_time' =>  'required',
            'location' =>  'required',
        ],[
            'event_id.required'     =>  trans("validation.required",['attribute'=>'event_id']),
            'title.required'     =>  trans("validation.required",['attribute'=>'Title']),
            'description.required'     =>  trans("validation.required",['attribute'=>'Description']),
            'image.required'     =>  trans("validation.required",['attribute'=>'Image']),
            'date.required'     =>  trans("validation.required",['attribute'=>'Date']),
            'time.required'     =>  trans("validation.required",['attribute'=>'Start Time']),
            'end_time.required'     =>  trans("validation.required",['attribute'=>'End Time']),
            'location.required'     =>  trans("validation.required",['attribute'=>'Location']),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $insert=[
                'title'=>$request->title,
                'description'=>$request->description,
                // 'image'=>$request->image,
                'date'=>$request->date,
                'time'=>$request->time,
                'end_time'=>$request->end_time,
                'location'=>$request->location
            ];
            if(!empty($request->image)){
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
                // $insert['image'] = $url;
            }
            if($request->reminder){
                $insert['is_reminder']="1";
            }
            $add = UserEvent::where('id',$request->event_id)->update($insert);
            
            if($add){
               
                if($request->reminder){
                    if(is_array($request->reminder)){
                        $reminders=$request->reminder;
                    }else{
                        $reminders=json_decode($request->reminder);
                    }
                    if($reminders){
                        DB::table('event_reminders')->where('event_id', $request->event_id)->delete();
                        foreach($reminders as $reminder){
                            if($reminder){
                                $insert=[
                                    'event_id'=>$request->event_id,
                                    'reminder_time'=>$reminder,
                                    'set_time'=>$reminder
                                ];
                                $addOption=EventReminder::create($insert);
                            }
                        }
                    }
                }
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Event updated successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while adding poll";
            }
        }
        // dd("ccc");

        return $this->populateResponse(); 
    }
    
    public function deleteEvent(Request $request){
        $validator = \Validator::make($request->all(), [
            'event_id' =>  'required'
            
        ],[
            'event_id.required'     =>  trans("validation.required",['attribute'=>'event_id'])
        ]);

        $validator->after(function($validator) use($request) {
            if($request->event_id){
                if(!UserEvent::where(['id'=>$request->event_id,'user_id'->Auth::guard('api')->id()])->first()){
                    $validator->errors()->add('event_id', trans('messages.F045'));
                }
            }
            
        });
        $add=UserEvent::where(['id'=>$request->event_id])->update(['status'=>"0"]);
            if($add){
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Your event deleted successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while deleting poll";
            }

        return $this->populateResponse();
    }
    
    public function updatePoll(Request $request){
         $validator = \Validator::make($request->all(), [
            'id' =>  'required',
            'question' =>  'required',
            'time' =>  'required',
            
        ],[
            'id.required'     =>  trans("validation.required",['attribute'=>'id']),
            'question.required'     =>  trans("validation.required",['attribute'=>'Question']),
            'time.required'     =>  trans("validation.required",['attribute'=>'Time']),
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $insert=[
                'question'=>$request->question,
                'type'=>'mcq',
                'time'=>$request->time
            ];
            $add=UserPoll::where('id',$request->id)->update($insert);
            if($add){
                if($request->options){
                    if(is_array($request->options)){
                        $options= $request->options;
                    }else{
                        $options=json_decode($request->options);
                        // if (json_last_error() === 0) {
                            // JSON is valid
                        // }
                    }
                    if($options){
                        foreach($options as $option){
                            if($option){
                                $insert=[
                                    'option'=>$option->text
                                ];
                                if($option->option_id && $option->text){
                                    $addOption=PollOption::where('id',$option->option_id)->update($insert);
                                }else if($option->option_id && $option->text==""){
                                    $addOption=PollOption::where('id',$option->option_id)->delete();
                                }else{
                                    $insert['poll_id']=$request->id;
                                    $addOption=PollOption::create($insert);
                                }
                                
                            }
                        }
                    }
                }
                $data = [];
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = "Poll updated successfully";
            }else{
                $this->status   = true;
                $this->message  = "Some error occured while adding poll";
            }
        }
        // dd("ccc");

        return $this->populateResponse(); 
    }
    
    public function myProfile(){
        $user=User::select('id','first_name','last_name','user_name','email','country_code','mobile_number','profile_pic','friend_profile_pic','family_profile_pic','work_profile_pic')->find(Auth::guard('api')->id());
        // $data=$user;
        $data['user'] = $user;
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'My profile was fetched successfully.';
        return $this->populateResponse(); 
    }

    public function editProfile(Request $request){
        $validator = \Validator::make($request->all(), [
            'first_name' =>  'required',
            'last_name' =>  'required'
        ],[
            'first_name.required'     =>  trans("validation.required",['attribute'=>'First name']),
            'last_name.required'     =>  trans("validation.required",['attribute'=>'Last name'])
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $update=[
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
            ];
            if ($request->profile_pic) {
                $image = $request->profile_pic;
                $filename = $image->getClientOriginalName();
                $filename = str_replace(" ", "", $filename);
                $imageName = time() . '.' . $filename;
                $return = $image->move(
                    base_path() . '/public/uploads/user/', $imageName);
                $url = url('/uploads/user/');
                $update['profile_pic'] = $url . '/' . $imageName;
            }
            if ($request->friend_profile_pic) {
                $image = $request->friend_profile_pic;
                $filename = $image->getClientOriginalName();
                $filename = str_replace(" ", "", $filename);
                $imageName = time() . '.' . $filename;
                $return = $image->move(
                    base_path() . '/public/uploads/user/', $imageName);
                $url = url('/uploads/user/');
                $update['friend_profile_pic'] = $url . '/' . $imageName;
            }
            if ($request->family_profile_pic) {
                $image = $request->family_profile_pic;
                $filename = $image->getClientOriginalName();
                $filename = str_replace(" ", "", $filename);
                $imageName = time() . '.' . $filename;
                $return = $image->move(
                    base_path() . '/public/uploads/user/', $imageName);
                $url = url('/uploads/user/');
                $update['family_profile_pic'] = $url . '/' . $imageName;
            }
            if ($request->work_profile_pic) {
                $image = $request->work_profile_pic;
                $filename = $image->getClientOriginalName();
                $filename = str_replace(" ", "", $filename);
                $imageName = time() . '.' . $filename;
                $return = $image->move(
                    base_path() . '/public/uploads/user/', $imageName);
                $url = url('/uploads/user/');
                $update['work_profile_pic'] = $url . '/' . $imageName;
            }
            User::where('id',Auth::guard('api')->id())->update($update);
            $user=User::select('id','first_name','last_name','user_name','email','country_code','mobile_number','profile_pic','friend_profile_pic','family_profile_pic','work_profile_pic')->find(Auth::guard('api')->id());
            $data=$user;
            $response = new \Lib\PopulateResponse($data);
            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = 'Profile updated successfully.';
           
        }
         return $this->populateResponse(); 
    }
    
     public function ringtones()
    {
        $events = Ringtone::where('status','active')->get();

        $data['ringtones']=$events;
        $response = new \Lib\PopulateResponse($data);

        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Ringtones fetched successfully.';
            return $this->populateResponse(); 
    }
    
    public function memberProfile(REQUEST $request){
        $user_list=[];
        $users=json_decode($request->user_id);
        if($users){
            // foreach($users as $user_id){
                $user=User::select('id','first_name','last_name','user_name','email','country_code','mobile_number','friend_profile_pic','family_profile_pic','work_profile_pic')->where('id',$request->user_id)->get();
                if(!empty($request->user_id)){
                     $check_group = assignContactGroup::select('group_name')->where('user_id',$request->user_id)->get();
               foreach($check_group as $check_groups)
               {
                   foreach($user as $users){
                    if(!empty($check_groups->group_name == "work")){
                      
                        $users->profile_pic = $users->work_profile_pic;
                           
                    
                    }
                    if(!empty($check_groups->group_name == 'family')){
                        
                        $users->profile_pic = $users->family_profile_pic;
                        
                    }

                    if(!empty($check_groups->group_name == 'friend')){
                        
                        $users->profile_pic  = $users->friend_profile_pic; 
                        
                    }
                    
                    
                  }
               }
                }
                if(!empty($user)){
                    $user->notifications=true;
                    $user->ringtone_id="0";
                    $user->type_of_user="";
                    $user->common_group=[
                        ['group_id'=>'1','group_name'=>'Group 1','group_image'=>'https://petclap.com/meenapp/public/uploads/events/1639568310.myfile.jpg'],['group_id'=>'2','group_name'=>'Group 2','group_image'=>'https://petclap.com/meenapp/public/uploads/events/1639568310.myfile.jpg']
                    ];
                    $user->shared_media=[
                        ['media_id'=>'1','file_path'=>'https://petclap.com/meenapp/public/uploads/events/1639568310.myfile.jpg']
                    ];
                    array_push($user_list,$user);
                // }
            }
        }
        $data = $user_list;
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Member profile fetched successfully.';
        return $this->populateResponse(); 
    }

    
    public function secheduleMessageSend(Request $request){
        $validator = \Validator::make($request->all(), [
            'chat_user_id' =>  'required',
            'receiver_user_id' =>  'required',
            'date'=>  'required',
            'time'=>  'required'
        ],[
            'chat_user_id.required'     =>  trans("validation.required",['attribute'=>'chat_user_id']),
            'receiver_user_id.required' =>  trans("validation.required",['attribute'=>'receiver_user_id']),
            'date.required'     =>  trans("validation.required",['attribute'=>'date']),
            'time.required'     =>  trans("validation.required",['attribute'=>'time'])
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });

        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $attachment="";
            if ($request->attachment) {
                $image = $request->attachment;
                $filename = $image->getClientOriginalName();
                $filename = str_replace(" ", "", $filename);
                $imageName = time() . '.' . $filename;
                $return = $image->move(
                    base_path() . '/public/uploads/attachments/', $imageName);
                $url = url('/uploads/attachments/');
                $attachment= $url . '/' . $imageName;
            }
            $data=ScheduleMessageSend::create(
                [
                    'user_id'=>Auth::guard('api')->id(),
                    'chat_user_id'=>$request->chat_user_id,
                    'chat_dialog_id'=>$request->chat_dialog_id,
                    'receiver_user_id'=>$request->receiver_user_id,
                    'send_to_chat'=>1,
                    'markable'=>1,
                    'message'=>$request->message,
                    'attachment'=>$attachment,
                    'date'=>$request->date,
                    'time'=>$request->time,
                    'status'=>'pending'
                ]
            );
            $data=[];
            $response = new \Lib\PopulateResponse($data);
            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = 'Message scheduled successfully.';
           
        }
         return $this->populateResponse(); 
    }
    
    public function secheduleMessageDelete(Request $request){
        $validator = \Validator::make($request->all(), [
            'chat_user_id' =>  'required',
            'chat_message_id' =>  'required',
            'date'=>  'required',
            'time'=>  'required'
        ],[
            'chat_user_id.required'     =>  trans("validation.required",['attribute'=>'chat_user_id']),
            'chat_message_id.required' =>  trans("validation.required",['attribute'=>'chat_message_id']),
            'date.required'     =>  trans("validation.required",['attribute'=>'date']),
            'time.required'     =>  trans("validation.required",['attribute'=>'time'])
        ]);

        $validator->after(function($validator) use($request) {
            
            
        });
        
        if ($validator->fails()) {
            $this->message = $validator->errors();
        }else{
            $data=ScheduleMessageDelete::create(
                [
                    'user_id'=>Auth::guard('api')->id(),
                    'chat_user_id'=>$request->chat_user_id,
                    'chat_message_id'=>$request->chat_message_id,
                    'date'=>$request->date,
                    'time'=>$request->time,
                    'status'=>'pending'
                ]
            );
            $data=[];
            $response = new \Lib\PopulateResponse($data);
            $this->data = $response->apiResponse();
            $this->status   = true;
            $this->message  = 'Delete message scheduled successfully.';
           
        }
         return $this->populateResponse(); 
    }
    
    public function eventDetail(REQUEST $request){
        $event = UserEvent::select('*')
        // ->where('user_id','<>',Auth::guard('api')->id())
        ->where('id',$request->event_id)
        ->where('status','1')
        ->first();
        if(!empty($event)){
                $event->time=date('h:i A',strtotime($event->time));
                $event->end_time=date('h:i A',strtotime($event->end_time));
                $event->reminder=EventReminder::where('event_id',$event->id)->get();
            $data=$event;
        }else{
            $data=new \stdClass();
        }
        
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Event details fetched successfully.';
        return $this->populateResponse(); 
    }
    
    public function pollDetail(REQUEST $request){
        $poll = UserPoll::select('*')->with('created_by','options')
        // ->where('user_id','<>',Auth::guard('api')->id())
        ->where('status','1')->where('id',$request->poll_id)->first();
        if(!empty($poll)){
            $data=$poll;
        }else{
            $data=new \stdClass();
        }
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Poll details fetched successfully.';
        return $this->populateResponse(); 
    }
    
    
    public function searchEvent(Request $request)
    {
      $event = $request->event;
      $events = UserEvent:: select('*')->where('title','like','%'.$event.'%')->where('status','1')->get();
      $data['events']=$events;
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Events fetched successfully.';
            return $this->populateResponse();
    }
    
    public function searchPoll(Request $request)
    {
      $question = $request->question;
      $polls = UserPoll:: select('*')->with('created_by','options')->where('question','like','%'.$question.'%')->where('status','1')->get();
      $data['polls']=$polls;
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'Polls fetched successfully.';
            return $this->populateResponse();
    }


    public function fileUpload(Request $request)
    {
       
        $validator = \Validator::make($request->all(), [
            'directory_id' => 'required',
            'file' => 'required'],
            [
                'directory_id.required' => trans('validation.required',['attribute' => 'directory_id']),
                'file.required' => trans('validation.required',['attribute' => 'file'])
            ]);
            $validator->after(function($validator) use($request) {
        });
            if ($validator->fails()) {
                $this->message = $validator->errors();
            }else{
                  $insert=[
                    'directory_id' => $request->directory_id,
                    // 'user_id' => Auth::guard('api')->id(),
                    'user_id' => $request->user_id
                    // 'file_type' => $request->file_type
                ];
                if ($request->hasFile('file')) {
                    $image = $request->file;
                     $size = $image->getSize();
                     $kb_size = round($size*1048576);
                    $filename = $image->getClientOriginalName();
                    $insert['file_name'] = $filename;
                    $filename = str_replace(" ", "", $filename);
                    $imageName = time() . '.' . $filename;
                    $imgExt=$image->getClientOriginalExtension();
                    $return = $image->move(
                        base_path() . '/public/uploads/gallary/images', $imageName);
                    $insert['file_path'] = "uploads/gallary/images/". $imageName;
                    $insert['file_size'] = $kb_size;
                    $insert['file_type'] = $imgExt;
                }
                $data = [];
                $add = DirectoryFile::create($insert);
                $file=DirectoryFile::find($add->id);
                $file['file_path']=url($file['file_path']);
                $data['file']=$file;
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = 'file uploaded successfully.';
                    return $this->populateResponse();
            }

    }

    public function fileDelete(Request $request)
    {
        $id = $request->id;
        $directory_id = $request->directory_id;
        $delete_file = DirectoryFile::where('id',$id)->where('directory_id',$directory_id);
        $delete = $delete_file->delete();
        $data = [];
        if($delete){
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status = true;
        $this->message = "data delete successfully";
         return $this->populateResponse();
        }else{
            $this->status   = true;
            $this->message  = "Some error occured while deleting file";
        }
    }

    public function directoryfileListing(Request $request){
           $directory_id = $request->directory_id;
           $file = DirectoryFile::where('directory_id',$directory_id)->where('status','active')->get();
           $data['file']=$file;
           $response = new \Lib\PopulateResponse($data);
           $this->data = $response->apiResponse();
           $this->status   = true;
           $this->message  = 'data fetched successfully.';
               return $this->populateResponse();
    }


    public function assign_contact_group(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'contact_user_id' => 'required',
            'group_name' => 'required'],
            [
                'contact_user_id.required' => trans('validation.required',['attribute' => 'contact_user_id']),
                'group_name.required' => trans('validation.required',['attribute' => 'group_name'])
            ]);

            $validator->after(function($validator) use($request) {
            
            
            });
            if ($validator->fails()) {
                $this->message = $validator->errors();
            }else{

                if(!empty($request->user_id)){
                    $exist_user_id = assignContactGroup::where('user_id',$request->user_id);
                     $delete = $exist_user_id->delete();
                 }
                  $insert=[
                    'user_id' => $request->user_id,
                    'contact_user_id' => $request->contact_user_id,
                    'group_name' =>$request->group_name    
                    
                ];
                
                $data = [];
               $add = assignContactGroup::create($insert);
                
               if($add)
               {
                $response = new \Lib\PopulateResponse($data);
                $this->data = $response->apiResponse();
                $this->status   = true;
                $this->message  = 'file insert successfully.';
                return $this->populateResponse();
               }else
               {
                    $this->status   = true;
                    $this->message  = "Some error occured while adding contact";
                }
            
           
            }

    }


    public function searchFile(Request $request)
    {
      $directory_id  = $request->file_name;
      $file_name = $request->file_name;
      $files = DirectoryFile::select('*')->where('file_name','like','%'.$file_name.'%')->orwhere('directory_id','like','%'.$directory_id.'%')->where('user_id','<>',auth::guard('api')->id())->where('status','active')->get();
      if($files){
      $data['files']=$files;
        $response = new \Lib\PopulateResponse($data);
        $this->data = $response->apiResponse();
        $this->status   = true;
        $this->message  = 'file fetched successfully.';
            return $this->populateResponse();
      }else{
        $this->status   = true;
        $this->message  = "Some error occured while fetch file";
      }
    }
}
