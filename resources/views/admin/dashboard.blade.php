@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Dashboard</h1> 
    </div>

    <div class="content"> 
        <div class="row">
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-green"><i class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number">{{$total_count}}</span> <span class="info-box-text">
                                Total Users </span> </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-green"><i class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span> {{$onlineactive_users}}<span class="info-box-text">
                                Total Online Users </span> </div>
                    </div>
                </div>
            </div>  
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-green"><i class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span>{{$total_subscribe}} <span class="info-box-text">
                                Total Subscribe Users </span> </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-green"><i class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span>{{$onlineactive_users}} <span class="info-box-text">
                                Total Active Users </span> </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-red"><i class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span>{{$total_freetrail}} <span class="info-box-text">
                                Free Trial Users </span> </div>
                    </div>
                </div>
            </div> 
        </div>  

        <div class="card mb-4">
            <div class="card-header mb-4">
                <h5 class="card-title">Recent Users</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile Number</th>
                                <th>Registration Date</th>  
                                <th>Status</th>  
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @if($users)
                            @foreach($users as $k=>$user)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$user->first_name}}&nbsp;&nbsp;{{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>+{{$user->country_code}}&nbsp;-&nbsp;{{$user->mobile_number}}</td>
                                <td>{{date('d-m-Y',strtotime($user->created_at))}}</td>
                                <td> {{$user->status}}
                                    <!-- @if($user->status=='active')
                                    <div class="mytoggle">
                                        <label class="switch">
                                            <input type="checkbox" <?= $user->status == 'active' ? 'checked' : '' ?> > <span class="slider round"> </span> 
                                        </label>
                                    </div>
                                    @endif -->
                                </td> 
                                <td><a href="{{url('admin/user-detail/'.base64_encode($user->id))}}" class="composemail"><i class="fa fa-eye"></i></a></td> 
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
    </div>
    

    @endsection