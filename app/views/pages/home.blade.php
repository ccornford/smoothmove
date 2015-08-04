@extends('layouts.master')

@section('title', 'Home')

@section('page-class', 'home')

@section('page-script')
  <script type="text/javascript">

  </script>
@stop

@section('content')
  <section class="hero" style="background-image: url('assets/images/hero.jpg');">
    <div class="hero-search">
      <h1>Find a new home</h1>
      {{ Form::open(array('route' => 'property.search', 'role' => 'search', 'class' => 'clear search-inline', 'method' => 'get')) }}

          {{ Form::text('s', Input::old('s'), array('placeholder'=>'e.g. Newcastle or NE1')) }}

          <button class="btn btn-orange"><i class="fa fa-search"></i></button>

      {{ Form::close() }}

      <p>Search for properties to rent in your city.</p>
    </div>
  </section>  <!-- END .hero -->

  <section class="property-listing">
    <h1>Latest Properties</h1>
    @if (isset($properties))

        <div class="properties properties-1of3">

          @include('partials.property-listing-grid')

        </div>

    @else
        <p>No properties found</p>
    @endif
  </section><!-- END .property-listing -->

  <section id="how-it-works" class="how-it-works grid grid--full large-grid--fit">
    <div class="container">
      <div class="grid grid--full large-grid--fit">
        <div class="grid-block">
           {{ HTML::image('/assets/images/icon-find.png', 'Find a home') }}
          <h3>Find places to live</h3>
          <p>Search thousands of properties available to rent in your area.</p>
        </div>
        <div class="grid-block">
          {{ HTML::image('/assets/images/icon-booking.png', 'Book a visit') }}
          <h3>Book a viewing</h3>
          <p>Contact property managers directly and organize a viewing.</p>
        </div>
        <div class="grid-block">
          {{ HTML::image('/assets/images/icon-home.png', 'Enjoy your new home') }}
          <h3>Enjoy your new home!</h3>
          <p>Once the paper work has been signed and verified.</p>
        </div>
      </div>
    </div>
  </section><!-- END .how-it-works -->
  <section id="sign-up" class="register">
    @if(!Sentry::check())
      <div class="register-form">
        @if($errors->any())
          <div class="flash error"><h3>Error</h3><span>{{ implode('', $errors->all('<p>:message</p>'))}}</span></div>
        @endif
        {{ Former::setOption('fetch_errors', false) }}

        {{ Former::open()->action('/register') }}
          <div class="form-group half">
            {{ Former::text('first_name')->placeholder('First name')->label('') }}

            {{ Former::text('last_name')->placeholder('Last name')->label('') }}
          </div>
            {{ Former::text('email')->placeholder('Email')->label('') }}

            {{ Former::password('password')->placeholder('Password')->label('') }}

            {{ Former::password('password_confirmation')->class('form-control')->placeholder('Confirm password')->label('') }}

            {{ Former::text('phone')->placeholder('Phone')->label('') }}


            <input class="btnlarge btnprimary btn" type="submit" value="Submit">

        {{ Former::close() }}
        <p>By creating an account you are agreeing to our <a href="">Terms of Service.</a></p>
      </div><!-- END .register-form -->
    @endif

    
    <div class="register-info">
      <h3>Take advantage of numerous advanced features by creating an account.</h3>
      <ul>
        <li>Subscribe to property listings.</li>
        <li>Upgrade to manager account.</li>
        <li>Save unlimited properties to favourites.</li>
      </ul>
    </div><!-- END .register-info -->
  </section><!-- END .register -->
    
@stop
