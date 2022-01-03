@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
  <div class="content-header sty-one">
    <h1>User Detail</h1> 
  </div>
  <div class="content userdetails">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="box-profile text-white">
              <div class="user-flag">
                <img class="profile-user-img img-responsive img-circle m-b-1" src="{{asset('/assets/admin/images/user.png')}}" alt="User profile picture">
              </div>
              <h3 class="profile-username text-center text-black">{{$user->first_name}}&nbsp;&nbsp;{{$user->last_name}}</h3> 
              <p class="text-dark text-center">{{$user->user_name}}
                
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-lg-4 col-xs-6 m-b-3">
        <div class="card">
          <div class="card-body"><span class="info-box-icon bg-green"><i class="icon-phone"></i></span>
            <div class="info-box-content"> <span class="info-box-number f-14">Mobile number</span>  <span class="info-box-text">{{$user->country_code}}&nbsp;-&nbsp;{{$user->mobile_number}} </span> 
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-xs-6 m-b-3">
        <div class="card">
          <div class="card-body"><span class="info-box-icon bg-red"><i class="icon-envelope"></i></span>
            <div class="info-box-content"> <span class="info-box-number f-14">Email Id </span> 
             <span class="info-box-text">{{$user->email}}</span> 
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-xs-6 m-b-3">
        <div class="card">
          <div class="card-body"><span class="info-box-icon bg-red">@foreach($user->user_poll as $user_poll)<a href="<?= url('admin/poll-detail/'.base64_encode($user_poll->id)) ?>"><i class="fa fa-signal" style="color:white;" aria-hidden="true"></i></a>@endforeach</span>
            <div class="info-box-content"> <span class="info-box-number f-14">Poll </span> 
             <span class="info-box-text">poll</span> 
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-xs-6 m-b-3">
        <div class="card">
          <div class="card-body"><span class="info-box-icon bg-red"><i class="fa fa-bullhorn"></i></span>
            <div class="info-box-content"> <span class="info-box-number f-14">Advertisement </span> 
             <span class="info-box-text">Add</span> 
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-xs-6 m-b-3">
        <div class="card">
          <div class="card-body"><span class="info-box-icon bg-yellow"><a href="<?= url('admin/event-management')?>"><i class="fa fa-calendar" style="color:white;" aria-hidden="true"></i></a></span>
            <div class="info-box-content"> <span class="info-box-number f-14">Events</span>  <span class="info-box-text">10k</span> 
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="card mt-4">
      <div class="card-header mb-4">
        <h5 class="card-title">Uploaded Videos</h5>
      </div>
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-lg-4 col-xs-6 m-b-3">
            <div class="form-group">
              <label>From</label>
              <input type="date" name="" class="form-control">
            </div>
          </div>
          <div class="col-lg-4 col-xs-6 m-b-3">
            <div class="form-group">
              <label>To</label>
              <input type="date" name="" class="form-control">
            </div>
          </div>
          <div class="col-lg-4 col-xs-6 mt-4">
            <button class="btn btn-primary pt-2 pb-2 mt-1 w-100">Search</button>
          </div>
        </div>
        <div class="table-responsive">
          <table id="example1" class="table table-bordered">
            <thead>
              <tr>
                <th>Sr. No.</th>
                <th>Video Title</th>
                <th>Video Type</th>
                <th>Category</th>
                <th>Posted On</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>01</td>
                <td>My Journey</td>
                <td>Challenge</td>
                <td>ABC</td>
                <td>10/07/2020</td>
                <td><a href="#" class="edit-composemail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="edit-composemail"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <tr>
                <td>01</td>
                <td>My Journey</td>
                <td>Challenge</td>
                <td>ABC</td>
                <td>10/07/2020</td>
                <td><a href="#" class="edit-composemail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="edit-composemail"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <tr>
                <td>01</td>
                <td>My Journey</td>
                <td>Challenge</td>
                <td>ABC</td>
                <td>10/07/2020</td>
                <td><a href="#" class="edit-composemail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="edit-composemail"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <tr>
                <td>01</td>
                <td>My Journey</td>
                <td>Challenge</td>
                <td>ABC</td>
                <td>10/07/2020</td>
                <td><a href="#" class="edit-composemail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="edit-composemail"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <tr>
                <td>01</td>
                <td>My Journey</td>
                <td>Challenge</td>
                <td>ABC</td>
                <td>10/07/2020</td>
                <td><a href="#" class="edit-composemail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="edit-composemail"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> -->
    
@endsection