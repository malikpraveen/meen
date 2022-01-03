@extends('admin.layout.master')

@section('content')

<div class="content-wrapper">
    <div class="content">
        <div class="card " >
            <div class="row " >
                <div class="col-lg-12">
                    <div class="user-profile-box" style="background-color: #ec1d38;">
                        <div class="box-profile text-white"> 
                            <img class="profile-user-img img-responsive img-circle m-b-2" style="margin:0" src= <?= $poll_detail->started_by->profile_pic?$poll_detail->started_by->profile_pic:asset('assets/admin/images/users/1.jpg') ?> alt="User profile picture">
                            <h3 class="profile-username">{{$poll_detail->started_by->user_name}}</h3>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class=" card eventdeatil"> 
            <div class=" card-body ">
                <div class="row">
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>Question</strong>
                        <br>
                        <p class="text-muted">{{$poll_detail->question}}</p>
                    </div>
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> <strong>Time</strong>
                        <br>
                        <p class="text-muted">{{$poll_detail->time}}</p>
                    </div>  
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>Email-id</strong>
                        <br>
                        <p class="text-muted">{{$poll_detail->started_by->email}}</p>
                    </div>
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> <strong>Mobile No</strong>
                        <br>
                        <p class="text-muted">{{$poll_detail->started_by->mobile_number}}</p>
                    </div> 
                     
                    <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                        <strong>OPTIONS</strong>
                        @foreach($poll_detail->options as $option1)
                        @if($loop->odd)
                        <br>
                        <p class="text-muted">{{$option1->option}}</p>
                        <div class="progress md-progress" style="height: 20px; width: 85%;">
                           <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{$option1->selection_percentage}}%; height: 20px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$option1->selection_percentage}}%</div>
                       </div>
                       @endif
                       @endforeach
                   
                   </div>
                   <div class="col-lg-6 col-xs-6 b-r mb-4"> 
                   @foreach($poll_detail->options as $option1)
                        @if($loop->even)
                       <br>
                        <p class="text-muted">{{$option1->option}}</p>
                        <div class="progress md-progress" style="height: 20px;  width: 85%;">
                           <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{$option1->selection_percentage}}%; height: 20px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$option1->selection_percentage}}%</div>
                       </div>
                       @endif
                       @endforeach
                    
                   </div>
                    
                </div> 
            </div>
        </div>
    </div>
@endsection