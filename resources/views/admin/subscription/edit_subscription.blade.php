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
           <form method="POST" id="addForm" enctype="multipart/form-data" action="{{url('admin/subscription/edit_update',[base64_encode($edit_subscription->id)])}}" >
                        @csrf
           <div class="card-body">
            
              <div class="col-md-6 mb-4 offset-3">
                 <div class="col-md-12 mb-4">
                     <label>Enter Name</label>
                     <input type="text" class="form-control validate" name="name"  value="{{ old('name', $edit_subscription['name']) }}"  placeholder="Enter name">
                      <p class="text-danger" id="nameError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Enter Validity</label>
                     <input type="text" class="form-control validate" name="validity" <?= $edit_subscription->validity ? 'readonly' : ''  ?> value="{{old('validity')?old('validity'):$edit_subscription->validity}}" placeholder="Enter validity">
                      <p class="text-danger" id="validityError"></p>
                   </div> 
                   <div class="col-md-12 mb-4">
                     <label>Enter Data Storage Validity (In Days)</label>
                     <input type="text" class="form-control validate" name="storage_validity" value="{{old('storage_validity')?old('storage_validity'):$edit_subscription->storage_validity}}" placeholder="Enter storage validity (In days)">
                      <p class="text-danger" id="storage_validityError"></p>
                   </div>
                   <div class="col-md-12 mb-4">
                     <label>Enter Size</label>
                     <input type="text" class="form-control validate" name="size"  value="{{old('size_gb')?old('size_gb'):$edit_subscription->size_gb}}" placeholder="Enter validity">
                      <p class="text-danger" id="sizeError"></p>
                   </div> 
                   <div class="col-md-12 mb-4">
                     <label>Enter Amount</label>
                     <input type="text" class="form-control validate" name="amount"  value="{{old('amount')?old('amount'):$edit_subscription->amount}}"  placeholder="Enter Amount">
                      <p class="text-danger" id="amountError"></p>
                   </div> 
                   <div class="text-center mb-4 mt-4">
                        <button type="button" onclick="validate(this);" class="btn btn-primary">Submit</button>
                
                    </div>
           
                </div>
                
           </div>
           </form>
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