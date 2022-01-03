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
           <form method="POST" id="addForm" enctype="multipart/form-data" action="{{url('admin/ringtone/update',[base64_encode($edit_ringtone->id)])}}">
                      @csrf
           <div class="card-body">
            
              <div class="col-md-6 mb-4 offset-3">
                 <div class="col-md-12 mb-4">
                     <label>Enter Ringtone Name</label>
                     <input type="text" class="form-control validate" name="name" value="{{old('name',$edit_ringtone['name'])}}"  placeholder="Enter name">
                      <p class="text-danger" id="nameError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Select Audio</label>
                     <input type="file" class="form-control validate"  name="audio" id="input"/>
                     <p class="text-danger" id="audioError"></p>
                      <audio id="sound" name="sound" controls> <source src="{{$edit_ringtone->audio}}" type="audio/mpeg"> </audio>
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