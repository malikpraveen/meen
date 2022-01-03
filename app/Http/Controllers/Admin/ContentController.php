<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;

class ContentController extends Controller
{
    public function index(Request $request){
        if(!Auth::guard('admin')->check()){
            return redirect()->intended('admin/login');
        }
        else{
            return view('admin.content.content_list');
        }
       
       
    }

    public function Content_list(Request $request){
        $data['content'] = Content::orderBy('id','asc')->get(); 
         return view('admin.content.content_list')->with($data);
      
    }

    public function content_edit(Request $request, $id=null){
        $id = base64_decode($id);
        $data['edit_content'] = Content::where('id',$id)->first();
        if($data){
            return view('admin.content.edit_content')->with($data);

        }else{
            return redirect()->back()->with('error','details not found');
        }
       
        

    }

    public function update_content(Request $request, $id=null){
        $id = base64_decode($id);
        $data = [ 
            
           'description' => $request->input('editor')
        
    ];
    $update = Content::find($id)->update($data);
    if($update){
        return redirect('admin/content-management')->with('success', ' update successfully.');
    }
    else {
        return redirect()->back()->with('error', 'Some error occurred while update ');
    }



    }
}
