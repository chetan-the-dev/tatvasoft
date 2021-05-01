@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<h2>Blog List</h2>
		@if(Auth::id())
		<a href="{{route('blog_add')}}" class="btn btn-success float-right">Add Blog</a>
		@endif
	</div>
  	<div class="row pt-2">
    @if(session()->has('error'))
	    <div class="alert alert-danger">
	        {{ session()->get('error') }}
	    </div>
	@elseif(session()->has('success'))
		<div class="alert alert-success">
	        {{ session()->get('success') }}
	    </div>
	@endif
	</div>        
  	<table class="table">
	    <thead>
	    	<th>Title</th>
	    	<th>Description</th>
	    	<th>Start Date</th>
	    	<th>End Date</th>
	    	<th>Image</th>
	    	@if(Auth::id())
	    	<th>Action</th>
	    	@endif
	    </thead>
	    <tbody>
	    	@foreach($get_data as $row)
	    	<tr>
	    		<td>{{$row->title}}</td>
	    		<td>{{$row->description}}</td>
	    		<td>{{$row->start_date}}</td>
	    		<td>{{$row->end_date}}</td>
	    		<td><img src="{{$row->image}}" width="50px"></td>
	    		@if(Auth::id())
	    		<td>
	    			<a href="{{route('blog_edit_form',['id'=>$row->id])}}" class="btn btn-success float-right">Edit</a>
	    			@if($row->is_active == 1)
	    			<a onclick="return confirm('Are you sure wanted to In-active blog?')" href="{{route('blog_status_update',['id'=>$row->id])}}" class="btn btn-success float-right">Active</a>
	    			@else
	    				<a onclick="return confirm('Are you sure wanted to active blog?')" href="{{route('blog_status_update',['id'=>$row->id])}}" class="btn btn-danger float-right">In Active</a>
	    			@endif
	    			<a onclick="return confirm('Are you sure wanted to remove blog?')" href="{{route('blog_delete',['id'=>$row->id])}}" class="btn btn-danger float-right">Delete</a>
	    		</td>
	    		@endif
	    	</tr>
	    	@endforeach
	    </tbody>
	</table>
</div>
@endsection