@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Content</h1> 
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
                <h5 class="card-title">Content</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                         @foreach($content as $key=>$content)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$content->name}}</td>
                                <td>
                                   <a href="<?= url('admin/edit_content/' . base64_encode($content->id)) ?>" class="composemail"><i class="fa fa-edit"></i></a>
                               </td> 
                            </tr>
                            @endforeach
                            <!-- <tr>
                                <td>2</td>
                                <td>Privacy Policy</td>
                                <td></td>
                                <td>
                                   <a href="#" class="composemail"><i class="fa fa-edit"></i></a>
                               </td> 
                            </tr> -->
                            <!-- <tr>
                                <td>3</td>
                                <td>Term of services</td>
                                <td></td>
                                <td>
                                   <a href="#" class="composemail"><i class="fa fa-edit"></i></a>
                               </td> 
                            </tr>
                          -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
    </div>
@endsection