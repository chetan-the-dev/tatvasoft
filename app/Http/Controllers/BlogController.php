<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\blog;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\File; 

class BlogController extends Controller
{
    //
	
    public function get_blog($value='')
    {
    	if(Auth::id() && Auth::user()->role=='1')
    	{
    		$get_data = blog::get();
    	}
    	elseif(Auth::id() && Auth::user()->role=='2')
    	{
    		$get_data = blog::where('user_id',Auth::id())->get();
    	}
    	else{
    		$get_data = blog::where('is_active',1)->whereDate('end_date','>',now())->get();
    	}
    	
    	$with = array(
    		'get_data'=>$get_data
    	);

    	return view('blog.view')->with($with);
    }

    public function blog_add_form($value='')
    {
    	return view('blog.add');
    }

    public function blog_add(Request $request)
    {
    	try {
    		$validation = Validator::make($request->all(),[
	    		'title'=>'required',
	    		'description'=>'required',
	    		'start_date'=>'required',
	    		'end_date'=>'required',
	    	]);

	    	if ($validation->fails()) {
	    		return redirect()->back()->withInputs()->withErrors($validation);
	    	}
	    	else{
	    		$add_data = new blog;
	    		$add_data->title = $request->title;
	    		$add_data->user_id = Auth::id();
	    		$add_data->description = $request->description;
	    		$add_data->start_date = $request->start_date;
	    		$add_data->end_date = $request->end_date;
	    		if ($request->hasfile('image')) {
	    			$name = $request->file('image')->getClientOriginalName();

	    			$request->file('image')->move('blog_images/',time().$name);
	    			$add_data->image = 'blog_images/'.time().$name;
	    		}
	    		$add_data->save();

	    		return redirect()->route('get_blog')->with('success','Blog Created');
	    	}
    	} catch (\Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}
    	
    }

    public function blog_edit_form($id)
    {
    	$get_data = blog::find($id);
    	return view('blog.edit')->with('get_data',$get_data);
    }

    public function blog_edit(Request $request)
    {
    	try {
    		$validation = Validator::make($request->all(),[
	    		'title'=>'required',
	    		'description'=>'required',
	    		'start_date'=>'required|date',
	    		'end_date'=>'required|date|after:start_date',
	    	]);

	    	if ($validation->fails()) {
	    		return redirect()->back()->withErrors($validation);
	    	}
	    	else{
	    		$add_data = blog::find($request->id);
	    		$add_data->title = $request->title;
	    		$add_data->description = $request->description;
	    		$add_data->start_date = $request->start_date;
	    		$add_data->end_date = $request->end_date;
	    		if ($request->hasfile('image')) {
	    			$name = $request->file('image')->getClientOriginalName();

	    			$request->file('image')->move('blog_images/',time().$name);
	    			$this->delete_file($get_data->image);
	    			$add_data->image = 'blog_images/'.time().$name;
	    		}
	    		$add_data->save();

	    		return redirect()->route('get_blog')->with('success','Blog Created');
	    	}
    	} catch (\Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}
    	
    }

    public function blog_status_update($id)
    {
    	$get_data = blog::find($id);
    	$get_data->is_active = $get_data->is_active== 1 ? 0 : 1;
    	$get_data->save();
    	return redirect()->back()->with('success','Blog updated');
    }

    public function blog_delete($id)
    {
    	$get_data = blog::find($id);
    	$this->delete_file($get_data->image);
    	$get_data->delete();
    	return redirect()->back()->with('success','Blog deleted');
    }

    public function delete_file($file_path)
    {
    	if (File::exists($file_path)) {
    		File::delete($file_path);
    	}
    }
}
