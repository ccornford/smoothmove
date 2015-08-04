@extends('layouts.master')
  
@section('content')
	<div class="forgot-wrapper">
		{{Form::open(array('url'=>'/forgot','method'=>'post'))}}
		<h1>Forgot Password</h1>
				 <span class="error">Email functionality is not currently configured.</span>

		<label for="email">Email</label>
		<div><input id="username" type="text" name="email" required="" value="{{Input::old('email')}}" /></div>
		<div>
			<input class="btn btn-orange" type="submit" value="Reset Password" />
		</div>
		 
		{{Form::close()}}
	</div>
@stop
