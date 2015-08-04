@extends('layouts.master')
  
@section('content')
	<div class="reset-wrapper">
		{{Form::open(array('url'=>'/reset','method'=>'post'))}}
		<h1>New Password</h1>
		 
		<input type="hidden" name="email" value="{{Input::get('email')}}" />
		<input type="hidden" name="resetcode" value="{{Input::get('resetcode')}}" />
		<div><input id="password" type="password" name="password" placeholder="New Password" required="" /></div>
		<div><input id="password" type="password" name="password_confirmation" placeholder="Confirm Password" required="" /></div>
		<div>
			<input type="submit" class="btn btn-orange" value="Save" />
		</div>
		 
		{{Form::close()}}
	</div>
@stop
