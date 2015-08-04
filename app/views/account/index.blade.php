<!-- app/views/account/index.blade.php -->

@extends('layouts.master')

@section('title', 'Account')

@section('page-class', 'account')

@section('page-script')
  <script type="text/javascript">

  </script>
@stop

@section('content')

  {{ Former::populate( $user ) }}

  <div class="container">

    <section class="edit-account">
      @if(isset($countAnswers))
        <div class="flash success"><span><a href="{{ URL::Route('faq.showQuestions', $property->id) }}"><i class="fa fa-envelope-o"></i> You have new replies</a></span></div>
      @endif
      <h1>Edit account details</h1>
      @if(Session::has('message'))
        <div class="flash success"><span>{{ Session::get('message') }}</span></div>
      @endif

      {{ Former::open()->method('post') }}

        {{ Former::text('first_name') }}
        {{ Former::text('last_name') }}
        {{ Former::text('phone') }}

        {{ Former::actions()->large_primary_submit('Save') }}

      {{ Former::close() }}
      <h2>Change password</h2>
      {{ Former::open()->method('post')->action('/account/newpassword') }}

        {{ Former::password('password')->label('New password') }}
        {{ Former::password('password_confirmation') }}
      
        {{ Former::actions()->large_primary_submit('Save') }}
      
      {{ Former::close() }}
    </section><!-- END .edit-account -->

    <section class="sidebar-nav">

      {{ HTML::linkRoute('favourites.index', 'View Favourites', [], ['class' => 'btn btn-orange']) }}
      <a href="{{ URL::Route('account.messages') }}" class="btn btn-orange">View Messages @if(isset($countAnswers)) <div class="notif">{{$countAnswers}}</div> @endif</a>

    </section>

    <section class="upgrade-account">
      <h1>Upgrade account</h1>

      <p>Would you like to list your own properties? Upgrade your account now!</p>

      @if(Session::has('upgrade-message'))
        <div class="flash success"><span>{{ Session::get('message') }}</span></div>
      @endif

      {{ Former::populate( $user ) }}

      {{ Former::open()->method('post')->action('/account/upgrade/') }}

        {{ Former::email('public_email') }}

        {{ Former::text('public_phone') }}

        {{ Former::text('street')->label('Street') }}

        {{ Former::text('town')->label('Town/City') }}

        {{ Former::text('county')->label('County') }}

        {{ Former::text('postcode')->label('Postcode') }}

        {{ Former::actions()->large_primary_submit('Save') }}

      {{ Former::close() }}

    </section><!-- END .upgrade-account -->

  </div>    
@stop
