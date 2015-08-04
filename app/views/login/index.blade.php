<!-- app/views/login/index.blade.php -->

@extends('layouts.master')

@section('title', 'Login')

@section('page-script')
    <script type="text/javascript">
        // my custom script
    </script>
@stop

@section('content')
    <p style="text-align:center;">Need an account? <a href="{{ URL::route('page.home') }}#sign-up">Sign Up</a></p>

    <div class="login-wrapper">
        @if(Session::has('error_msg'))
            <div class="alert alert-danger">{{Session::get('error_msg')}}</div>
        @endif
        {{ implode('', $errors->all('<div class="alert alert-danger">:message</div>'))}}

        {{ Form::open(array('url' => 'login')) }}
        <h1>Account login</h1>

        <!-- if there are login errors, show them here -->

            {{ Form::label('email', 'Email Address') }}
            {{ Form::text('email', Input::old('email')) }}

            {{ Form::label('password', 'Password') }}
            {{ Form::password('password') }}
            <a href="/forgot">Forgot password</a>

            {{ Form::submit('Submit', array('class' => 'btn btn-submit')) }}
        {{ Form::close() }}
    </div>


@stop
