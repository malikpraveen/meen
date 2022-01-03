@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Help & Support</h1>
    </div>
    <a href="<?= url('admin/manage_subject')?>"  class="btn btn-primary pt-2 pb-2 w-45 mt-1 float-center" style="position: relative; left: 80%;" >Manage Subject</a>
    <div class="content"> 
      <div class="card mb-2">
            <div class="card-body">
                <form method="post" action="{{route('admin.query.filter')}}">
                    @csrf
                    <div class="row"> 
                        <div class="col-md-4 col-xs-6">
                            <div class="form-group">
                                <label>From </label>
                                <input type="date" onchange="$('#start_date').attr('min', $(this).val());" max="<?= date('Y-m-d') ?>"  value="{{isset($start_date)?$start_date:''}}"  name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="form-group">
                                <label>To </label>
                                <input type="date" id="start_date" name="end_date" max="<?= date('Y-m-d') ?>" value="{{isset($end_date)?$end_date:''}}" class="form-control">
                            </div>
                        </div> 
                        <div class="col-md-12 col-xs-12">
                            <p id="formError" class="text-danger"></p>
                        </div>
                        <div class="col-md-4 col-xs-6 mt-1">
                        <a href="#filter" onclick="filterList(this)"; class="btn btn-primary pt-2 pb-2 w-100 mt-1">Search</a>
                        </div> 
                        <div class="col-md-4 col-xs-6 mt-1">
                            <a href='<?= url('admin/query-management') ?>' class="btn btn-primary pt-2 pb-2 w-100 mt-1">Reset</a>
                        </div>     
                    </div> 
                </form>  
            </div>   
        </div>   

        <div class="row">  
            <div class="col-md-12"> 
                <div class="card"> 
                   <div class="card-header mb-4">
                      <h5 class="card-title">Listing</h5>
                   </div> 
                    <div class="card-body"> 
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
                        <div class="table-responsive table-image">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>  
                                        <th>User Name </th>    
                                        <th>Subject</th>  
                                        <th>Query Date and Time</th> 
                                        <th>Status</th> 
                                        <th>Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($query as $key=>$query)
                                    <tr> 
                                        <td>{{$key+1}}</td>
                                        <td>{{$query->user->user_name}}</a></td> 
                                        <td>{{$query->support_subject->subject}}</td>
                                        <td>{{$query->created_at->format('d-M-Y  H:i:s')}}</td>
                                        @if($query->status=='0')
                                        <td><span class="text-danger">Unseen</span></td> 
                                        <!-- <td><span class="text-{{$query->status==1?'success':'danger'}}">{{$query->status==1?'seen':'unseen'}}</td> -->
                                        @else
                                        @if($query->status=='1')
                                        <td><span class="text-success">Seen</span></td> 
                                        @endif
                                        @endif
                                        <td><a class="composemail" href='<?= url('admin/query-detail/'.base64_encode($query->id)) ?>'><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                    <!-- <tr> 
                                        <td class="property-link"><a href="#"></a></td>
                                        <td class="property-link"><a href=""></a></td> 
                                        <td></td>
                                        <td></td>
                                        <td><span class="text-danger">Unseen</span></td>  
                                       
                                    </tr> -->
                                    <!-- <tr> 
                                        <td class="property-link"><a href="#"></a></td>
                                        <td class="property-link"><a href=""></a></td> 
                                        <td></td>
                                        <td></td>
                                        <td><span class="text-success">Seen</span></td> 
                                       
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
           </div>
      </div>
   </div>
 @endsection

 <script>
       function filterList(obj){
        if ($(':input[name=start_date]').val() == '' && $(':input[name=end_date]').val() == ''){
        $("#formError").html('Select filter attribute');
        } else{

        if ($(':input[name=start_date]').val() != '' && $(':input[name=end_date]').val() != ''){
        $('form').submit();
        } else{
        if ($(':input[name=start_date]').val() != ''){
        $("#formError").html('End date is required');
        } else if ($(':input[name=end_date]').val() != ''){
        $("#formError").html('Start date is required');
        } else{
        $("#formError").html('Select filter attribute');
        }
        }
        }

        }
    
 </script>