@extends('admin.layout.master')

@section('content')

<div class="content-wrapper">
    <div class="content">
        <div class="card">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-box">
                        <div class="box-profile text-white"> 
                            <img class="profile-user-img img-responsive img-circle m-b-2" style="margin:0" src='<?= $userdata[0]->profile_pic?$userdata[0]->profile_pic:asset('assets/admin/images/users/1.jpg') ?>' alt="User profile picture">
                            <h3 class="profile-username">{{$userdata[0]->user_name}}</h3>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class=" card eventdeatil"> 
            <div class=" card-body ">
                <div class="row">
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>Title</strong>
                        <br>
                        <p class="text-muted">{{$eventdetail->title}}</p>
                    </div>
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> <strong>Description</strong>
                        <br>
                        <p class="text-muted">{{$eventdetail->description}}</p>
                    </div>  
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>Date & Time</strong>
                        <br>
                        <p class="text-muted">{{$eventdetail->date}} & {{$eventdetail->time}}</p>
                    </div>
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> <strong>Location</strong>
                        <br>
                        <p class="text-muted">Noida nhi hai</p>
                    </div>  
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>Email-Id</strong>
                        <br>
                        <p class="text-muted">{{$userdata[0]->email}}</p>
                    </div>
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> <strong>Mobile No</strong>
                        <br>
                        <p class="text-muted">{{$userdata[0]->mobile_number}}</p>
                    </div>  
                </div> 
            </div>
        </div>
    </div>
@endsection