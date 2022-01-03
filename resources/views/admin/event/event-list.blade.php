@extends('admin.layout.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Status: <span class="text-success"></span></h1>
    </div>
   
    <div class="content userdetails">
           @if(session()->has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('success') }}
                </div>
                @else 
              @if(session()->has('error'))  
                  <div class="alert alert-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                       {{ session()->get('error') }}
                   </div>
                @endif 
           @endif
        <div class="row m-b-3">
            <div class="col-md-12" id="tabDiv">
                <div class="card">
                    <ul class="nav nav-pills m-t-30 m-b-30">
                        <li class=" nav-item"> <a href="#Profile_Detail" class="nav-link active" data-toggle="tab" aria-expanded="false">Upcoming Event</a> </li>
                        <li class="nav-item"> <a href="#Game_History" class="nav-link" data-toggle="tab" aria-expanded="false">Ongoing Event</a> </li>
                        <li class="nav-item"> <a href="#Transaction_Detail" class="nav-link" data-toggle="tab" aria-expanded="true">Overdue</a> </li>
                    </ul>
                    <div class="tab-content br-n m-t-2">
                        <div id="Profile_Detail" class="tab-pane active">
                            <div class="content">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>User Name</th>
                                                        <th>Image</th>
                                                        <th>Date & Time</th> 
                                                        <th>Status</th>
                                                        <th>Created At</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($upcoming_event)
                                                    @foreach($upcoming_event as $key=>$upcomingevent)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$upcomingevent->title}}</td>
                                                        <td>{{$upcomingevent->user_name}}</td>
                                                        <td><img class="img-responsive" src="<?= $upcomingevent->image?$upcomingevent->image:asset('assets/admin/images/users/1.jpg') ?>"></td>
                                                        <td>{{$upcomingevent->date}} & {{$upcomingevent->time}}</td>
                                                        <td> <div class="mytoggle">
                                                            <label class="switch">
                                                           <input type="checkbox" onchange="changeStatus(this, '<?= $upcomingevent->id ?>');" <?= ( $upcomingevent->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                                            </label>
                                                       </div></td>
                                                        <td>{{date('d-m-y',strtotime($upcomingevent->created_at))}}</td>
                                                        <td><a class="mr-3"   href='<?= url('admin/event-detail/'.base64_encode($upcomingevent->id)) ?>'>View</a><a href='#' onclick="deleteData(this,'{{$upcomingevent->id}}');">Delete</a></td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                    <!-- <tr>
                                                        <td>2</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <td>3</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <td>4</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="Game_History" class="tab-pane">
                            <div class="content">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>User Name</th>
                                                        <th>Image</th>
                                                        <th>Date & Time</th>
                                                        <th>Status</th> 
                                                        <th>Created At</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($ongoing_event)
                                                    @foreach($ongoing_event as $key=>$ongoingevent)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$ongoingevent->title}}</td>
                                                        <td>{{$ongoingevent->user_name}}</td>
                                                        <td><img class="img-responsive" src="<?= $ongoingevent->image?$ongoingevent->image:asset('assets/admin/images/users/1.jpg') ?>"></td>
                                                        <td>{{$ongoingevent->date}} & {{$ongoingevent->time}}</td>
                                                        <td> <div class="mytoggle">
                                                            <label class="switch">
                                                           <input type="checkbox" onchange="changeStatus(this, '<?= $ongoingevent->id ?>');" <?= ( $ongoingevent->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                                             </label>
                                                         </div></td>
                                                        <td>{{date('d-m-y',strtotime($ongoingevent->created_at))}}</td>
                                                        <td><a class="mr-3" href='<?= url('admin/event-detail/'.base64_encode($ongoingevent->id)) ?>'>View</a><a href='#' onclick="deleteData(this,'{{$ongoingevent->id}}');">Delete</a></td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                    <!-- <tr>
                                                        <td>2</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <td>3</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <td>4</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div id="Transaction_Detail" class="tab-pane">
                            <div class="content">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>User Name</th>
                                                        <th>Image</th>
                                                        <th>Date & Time</th>
                                                        <th>Status</th> 
                                                        <th>Created At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(@over_due_event)
                                                    @foreach($over_due_event as $key=>$overdue_event)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$overdue_event->title}}</td>
                                                        <td>{{$overdue_event->user_name}}</td>
                                                        <td><img class="img-responsive" src="<?= $overdue_event->image?$overdue_event->image:asset('assets/admin/images/users/1.jpg') ?>"></td>
                                                        <td>{{$overdue_event->date}} & {{$overdue_event->time}}</td>
                                                        <td> <div class="mytoggle">
                                                             <label class="switch">
                                                             <input type="checkbox" onchange="changeStatus(this, '<?= $overdue_event->id ?>');" <?= ( $overdue_event->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                                           </label>
                                                          </div></td>
                                                        <td>{{date('d-m-y',strtotime($overdue_event->created_at))}}</td>
                                                        <td><a class="mr-3" href='<?= url('admin/event-detail/'.base64_encode($overdue_event->id)) ?>'>View</a><a href='#' onclick="deleteData(this,'{{$overdue_event->id}}');" class="delete-confirm">Delete</a></td>
                                                    </tr>
                                                    
                                                    @endforeach
                                                    @endif
                                                    <!-- <tr>
                                                        <td>2</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <td>3</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr>
                                                    <tr> -->
                                                        <!-- <td>4</td>
                                                        <td>Title name</td>
                                                        <td>Srijan</td>
                                                        <td><img class="img-responsive" src="{{asset('assets/admin/images/users/1.jpg')}}"></td>
                                                        <td>16/11/21 & 03.00PM</td>
                                                        <td><a class="mr-3" href="{{url('admin/event-detail')}}">View</a><a href="">Delete</a></td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
      function deleteData(obj, id){
            //var csrf_token=$('meta[name="csrf_token"]').attr('content');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this record!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url : "<?= url('admin/event-delete') ?>",
                        type : "POST",
                        data : 'id=' + id + '&_token=<?= csrf_token() ?>',
                        success: function(data){
                            swal({
                                title: "Success!",
                                text : "Event has been deleted \n Click OK to refresh the page",
                                icon : "success",
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error : function(){
                            swal({
                                title: 'Opps...',
                                text : data.message,
                                type : 'error',
                                timer : '1500'
                            })
                        }
                    })
                } else {
                swal("Your event file is safe!");
                }
            });
        }


        
</script>
<script>
       function changeStatus(obj, id) {
            swal({
                title: "Are you sure?",
                text: " status will be updated",
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true,
            })
                    .then((willDelete) => {
                        if (willDelete) {
                            var checked = $(obj).is(':checked');
                            if (checked == true) {
                                var status = 'active';
                            } else {
                                var status = 'inactive';
                            }
                            if (id) {
                                $.ajax({
                                    url: "<?= url('admin/event/change_status') ?>",
                                    type: 'post',
                                    data: 'id=' + id + '&action=' + status + '&_token=<?= csrf_token() ?>',
                                    success: function (data) {
                                    
                                        swal({
                                           title: "Success!",
                                            text : "event Status has been Updated \n Click OK to refresh the page",
                                            icon : "success",
                                        })
                                    }
                                });
                            } else {
                                var data = {message: "Something went wrong"};
                                errorOccured(data);
                            }
                        } else {
                            var checked = $(obj).is(':checked');
                            if (checked == true) {
                                $(obj).prop('checked', false);
                            } else {
                                $(obj).prop('checked', true);
                            }
                            return false;
                        }
                    });
        }
    </script>