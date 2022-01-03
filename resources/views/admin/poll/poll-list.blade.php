@extends('admin.layout.master')

@section('content')
   <div class="content-wrapper">
        <div class="content-header sty-one">
            <h1>Poll</h1> 
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
          <div class="card mb-4">
              <div class="card-header mb-4">
                  <h5 class="card-title">Poll</h5>
                </div>
              <div class="card-body">
                   <div class="table-responsive">
                        <table id="example1" class="table table-bordered">
                           <thead>
                               <tr>
                                   <th>Sr. No.</th>
                                   <th> Question</th>
                                    <th>Started By</th>
                                    <th>Status </th>
                                    <th>Created At</th>  
                                    <th>Action</th> 
                               </tr>
                            </thead>
                            <tbody>
                               @if($poll_list)
                                    @foreach($poll_list as $key=>$poll)

                                      <tr>
                                         <td>{{$key+1}}</td>
                                          <td>{{$poll->question}}</td>
                                          <td>{{$poll->started_by->first_name}}&nbsp;&nbsp;{{$poll->started_by->last_name}}</td>
                                          <td> <div class="mytoggle">
                                        <label class="switch">
                                        <input type="checkbox" onchange="changeStatus(this, '<?= $poll->id ?>');" <?= ( $poll->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                        </label>
                                    </div></td>
                                          <td>{{$poll->created_at->format('d/m/Y')}}</td>
                                          <td><a class="composemail" href='<?= url('admin/poll-detail/'.base64_encode($poll->id)) ?>'><i class="fa fa-eye"></i></a><a href='#' onclick="deleteData(this,'{{$poll->id}}');" class="composemail"><i class="fa fa-trash"></i></a></td>
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
                        url : "<?= url('admin/poll-delete') ?>",
                        type : "POST",
                        data : 'id=' + id + '&_token=<?= csrf_token() ?>',
                        success: function(data){
                            swal({
                                title: "Success!",
                                text : "Poll has been deleted \n Click OK to refresh the page",
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
                swal("Your poll file is safe!");
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
                                    url: "<?= url('admin/poll/change_status') ?>",
                                    type: 'post',
                                    data: 'id=' + id + '&action=' + status + '&_token=<?= csrf_token() ?>',
                                    success: function (data) {
                                    
                                        swal({
                                           title: "Success!",
                                            text : "Poll Status has been Updated \n Click OK to refresh the page",
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