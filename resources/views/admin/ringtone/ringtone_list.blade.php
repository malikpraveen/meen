@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Ringtone</h1> 
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
               <h5 class="card-title">Add Ringtone</h5>
           </div>
           <form method="POST" id="addForm" enctype="multipart/form-data" action="{{url('admin/ringtone/submit')}}" >
                      @csrf
           <div class="card-body">
            
              <div class="col-md-6 mb-4 offset-3">
                 <div class="col-md-12 mb-4">
                     <label>Enter Ringtone Name</label>
                     <input type="text" class="form-control validate" name="name"  placeholder="Enter name">
                      <p class="text-danger" id="nameError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Select Audio</label>
                     <input type="file" class="form-control validate" name="audio" id="input"/>
                     <p class="text-danger" id="audioError"></p>
                      <audio id="sound" name="sound" controls></audio>
                   </div> 
                   <!-- <div class="col-md-12 mb-4">
                     <label>Select Image</label><br>
                     <img id="blah"  src="{{ asset('assets/admin/images/dummy.jpg')}}" alt="your image" width="100" height="100" />
                     <input type="file" 
                     onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                   </div>  -->
                   
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
                <h5 class="card-title">Ringtone</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th> Name</th>
                                <th>Audio</th>
                                <th>Status</th>  
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                         @foreach($ringtone as $key=>$ringtones)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$ringtones->name}}</td>
                                <td>  <audio  controls preload="none" onplay="pauseOthers(this);">
                                     <source src="{{$ringtones->audio}}"  type="audio/mpeg"> 
                                    </audio>
                                    
                                </td>
                            
                                <td> <div class="mytoggle">
                                        <label class="switch">
                                        <input type="checkbox" onchange="changeStatus(this, '<?= $ringtones->id ?>');" <?= ( $ringtones->status == 'active' ? 'checked' : '') ?>><span class="slider round"> </span> 
                                        </label>
                                    </div></td>
                              
                                <td>
                                  <a href="<?=url('admin/ringtone_edit/'. base64_encode($ringtones->id))?>" class="composemail"><i class="fa fa-edit"></i></a>
                                  <a href='#' onclick="deleteData(this,'{{$ringtones->id}}');" class="composemail"><i class="fa fa-trash"></i></a>
                            
                                </td> 
                            </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
      
    </div>
   
    
    <script>
       input.onchange = function(e){
       var sound = document.getElementById('sound');
        sound.src = URL.createObjectURL(this.files[0]);
 
       sound.onend = function(e) {
        URL.revokeObjectURL(this.src);
         }
         }
    </script>
  
   
    @endsection
    <script>
    function pauseOthers(element){
        $("audio").not(element).each(function(index,audio){
            audio.pause();
        })
    }

</script>
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
                                    url: "<?= url('admin/ringtone/change_status') ?>",
                                    type: 'post',
                                    data: 'id=' + id + '&action=' + status + '&_token=<?= csrf_token() ?>',
                                    success: function (data) {
                                    
                                        swal({
                                           title: "Success!",
                                            text : "ringtone Status has been Updated \n Click OK to refresh the page",
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
                        url : "<?= url('admin/ringtone-delete') ?>",
                        type : "POST",
                        data : 'id=' + id + '&_token=<?= csrf_token() ?>',
                        success: function(data){
                            swal({
                                title: "Success!",
                                text : "ringtone has been deleted \n Click OK to refresh the page",
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
                swal("Your ringtone file is safe!");
                }
            });
        }


        
</script>