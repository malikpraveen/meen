@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Subscription</h1> 
    </div>

    <div class="content">
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
       <div class="card mb-2">
            <div class="card-header mb-4">
               <h5 class="card-title">Add Subscription</h5>
           </div>
           <form method="POST" id="addForm" enctype="multipart/form-data" action="{{url('admin/subscription/submit')}}" >
                      @csrf
           <div class="card-body">
            
              <div class="col-md-6 mb-4 offset-3">
                 <div class="col-md-12 mb-4">
                     <label>Enter Name</label>
                     <input type="text" class="form-control validate" name="name"  placeholder="Enter name">
                      <p class="text-danger" id="nameError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Enter Plan Validity</label>
                     <input type="text" class="form-control validate" name="validity"  placeholder="Enter plan validity">
                      <p class="text-danger" id="validityError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Enter Data Storage Validity (In days)</label>
                     <input type="text" class="form-control validate" name="storage_validity"  placeholder="Enter data storage validity (In Days)">
                      <p class="text-danger" id="storage_validityError"></p>
                   </div>  
                   <div class="col-md-12 mb-4">
                     <label>Enter Size</label>
                     <input type="text" class="form-control validate" name="size"  placeholder="Enter size in GB">
                      <p class="text-danger" id="sizeError"></p>
                   </div> 
                   <div class="col-md-12 mb-4">
                     <label>Enter Amount</label>
                     <input type="text" class="form-control validate" name="amount"  placeholder="Enter Amount">
                      <p class="text-danger" id="amountError"></p>
                   </div> 
                   <div class="text-center mb-4 mt-4">
                        <button type="button" onclick="validate(this);" class="btn btn-primary">Submit</button>
                
                    </div>
           
                </div>
                
           </div>
           </form>
       </div>
        <!-- <div class="row">
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span> <span class="info-box-text">
                                Events </span> </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-red"><i class=" fa fa-signal"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span> <span class="info-box-text">
                                Poll </span> </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-6 col-xs-6 m-b-3">
                <div class="card">
                    <div class="card-body"><span class="info-box-icon bg-red"><i class="fa fa-bullhorn"></i></span>
                        <div class="info-box-content"> <span class="info-box-number"></span> <span class="info-box-text">
                                Advertisement </span> </div>
                    </div>
                </div>
            </div>  
        </div>   -->

        <div class="card mb-4">
            <div class="card-header mb-4">
                <h5 class="card-title">Subscription</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th> Name</th>
                                <th>Validity</th>
                                <th>Memory Validity</th>
                                <th>Size</th>
                                <th>Amount</th> 
                                <th>Total Subscription</th>
                                <th>Status</th>  
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                        @if($subscription_data)
                        @foreach($subscription_data as $key=>$subscriptions)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$subscriptions->name}}</td>
                                <td>{{$subscriptions->validity}} days</td>
                                <td>{{$subscriptions->storage_validity}} days</td>
                                <td>{{$subscriptions->size_gb}} GB</td>
                                <td>{{$subscriptions->amount}}</td>
                                <td>0</td>
                            
                                <td> <div class="mytoggle">
                                        <label class="switch">
                                        <input type="checkbox" onchange="changeStatus(this, '<?= $subscriptions->id ?>');" <?= ( $subscriptions->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                        </label>
                                    </div></td>
                              
                                <td><a href="#" class="composemail"><i class="fa fa-eye"></i></a>
                                <a href="<?= url('admin/edit_subscription/' . base64_encode($subscriptions->id)) ?>" class="composemail"><i class="fa fa-edit"></i></a>
                            
                            </td> 
                               

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

    <script>
            function validate(obj) {
            $(".text-danger").html('');
            var flag = true;
            var formData = $("#addForm").find(".validate:input").not(':input[type=button]');
            $(formData).each(function () {
                var element = $(this);
                var val = element.val();
                var name = element.attr("name");
                if (val == "" || val == "0" || val == null) {
                    
                $("#" + name + "Error").html("This field is required");
                flag = false;
                    
                    
                } else {

                }
            });
           
            if (flag) {
                $("#addForm").submit();
            } else {
                return false;
            }

            
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
                                    url: "<?= url('admin/subscription/change_status') ?>",
                                    type: 'post',
                                    data: 'id=' + id + '&action=' + status + '&_token=<?= csrf_token() ?>',
                                    success: function (data) {
                                    
                                        swal({
                                           title: "Success!",
                                            text : "Subscription Status has been Updated \n Click OK to refresh the page",
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