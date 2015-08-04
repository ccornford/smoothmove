<!-- app/views/property/details/index.blade.php -->

@extends('layouts.master')

@section('title', 'Details')

@section('page-class', 'details')

@section('page-script')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;language=en"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		map();
	    slideshow();
	    initSticky();
	});
	function slideshow() {
		var elements = $(".thumbnail").length;
		$('.thumbnail').click(function(){
			var src = $(this).data('src');

			$('.active').removeClass('active');
			$(this).addClass('active');
			$('#focused img').attr("src", src);
		});
	}

	function map() {
	    var mapOptions = {
		        zoom: 14,
		        center: new google.maps.LatLng({{ $property->latitude }}, {{ $property->longitude }}),
		        zoomControl: true,
	            zoomControlOptions: {
	                style: google.maps.ZoomControlStyle.SMALL,
	            },
	            disableDoubleClickZoom: true,
	            mapTypeControl: true,
	            mapTypeControlOptions: {
	                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
	            },
	            scaleControl: false,
	            scrollwheel: true,
	            panControl: false,
	            streetViewControl: true,
	            draggable : true,
	            overviewMapControl: true,
	            overviewMapControlOptions: {
	                opened: false,
	            },
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
	        };

	    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);


	    var myMarker = new google.maps.Marker({
			position: new google.maps.LatLng({{ $property->latitude }}, {{ $property->longitude }}),
			map: map,
			icon: '/assets/images/icon-pin.png'
	    });
	}

	function initSticky() {
	    // Check the initial Poistion of the Sticky Header
	    var stickyHeaderTop = $('#stickyheader').offset().top,
	        stickySidebarTop = $('.sidebar-group').offset().top;

	    $(window).scroll(function(){
    		var scroll = Math.round($(window).scrollTop()),
    			navTrack = $('tack-nav'),
    			bottom = $(".footer").offset().top - scroll - 100;

            if( scroll > stickyHeaderTop ) {
                    $('#stickyheader').addClass('active');
            } else {
                    $('#stickyheader').removeClass('active');
            }

          	if(isDesktop()){

	            if( bottom < $('.sidebar-group').height() ) {
	                $('.sidebar-group').css({
			            position: 'absolute',
			            top: $(".footer").offset().top - $('.sidebar-group').height() - 20
			        });
	            } else if( scroll > stickyHeaderTop ) {
	                $('.sidebar-group').addClass('active');
	                $('.sidebar-group').removeAttr("style");
	            } else {
	            	$('.sidebar-group').removeClass('active');
	            }
	        }

	    });
	}

</script>
@stop

@section('content')
	<div class="container">
	 	@if(Session::has('message'))
	        <div class="flash success"><span>{{ Session::get('message') }}</span></div>
	    @endif
		<h2 class="title">{{ $property->street }}, {{ $property->town }}, {{ $property->county }} - {{ $property->bedrooms }} @if($property->bedrooms != 1) Bedrooms @else Bedroom @endif {{ $property->type->name }}</h2>
	</div>

	<div id="stickyheader"  role="property navigation">
		<div class="container">
			<ul class="nav-pills">
				<li><a href="#overview">Overview</a></li>
				<li><a href="#features">Features</a></li>
				<li><a href="#faq">FAQ</a></li>
				<li><a href="#location">Location</a></li>
				<li><a href="#photos">Photos</a></li>
				<li>
					@if(Sentry::check() && UserFavourite::where('prop_id', $property->id)->where('user_id', Sentry::getUser()->id)->first())	 
						{{ Form::open(array('action' => array('UserFavouriteController@destroy', $property->id), 'method' => 'delete')) }}
			        		<button type="submit" class="btn btn-fav open"> Unfavourite</button>
				    	{{ Form::close() }}
				    @else
					  	{{ Form::open(array('action' => array('UserFavouriteController@store', $property->id), 'method' => 'post')) }}
				        	<button type="submit" class="btn btn-fav"> Favourite</button>
				    	{{ Form::close() }}
				    @endif
				</li>
			</ul>
		</div>
	</div>
	<div class="container">
	    @if($property->let_agreed == 1)
			<section id="overview" class="slideshow tracknav property-let-agreed">
	    @else
			<section id="overview" class="slideshow track-nav">
	    @endif
			<div id="focused">{{ HTML::image($property->upload[0]->media->url('medium').$property->upload[0]->media->filename) }}</div>
			<div class="thumbs">
				@foreach($property->upload as $image)
					<div class="thumbnail" data-src="{{ $image->media->url('medium').$image->media->filename }}">
						{{ HTML::image($image->media->url('thumb').$image->media->filename) }}
					</div>
				@endforeach
			</div>
		</section><!-- END .slideshow -->
		<div class="sidebar">
			<div class="sidebar-group">
				<section class="property-amenities">
					<table class="table-striped">
						<tbody>
							<caption>Property Details</caption>
							<tr>
								<td>Bedrooms</td>
								<td>{{ $property->bedrooms }}</td>
							</tr>
							<tr>
								<td>Bathrooms</td>
								<td>{{ $property->bathrooms }}</td>
							</tr>
							<tr>
								<td>Type</td>
								<td>{{ $property->type->name }}</td>
							</tr>
							<tr>
								<td>Furnished</td>
								<td>{{ $property->furnished->name }}</td>
							</tr>
							<tr>
								<td>Garden</td>
								<td>{{ $property->garden->name }}</td>
							</tr>
							<tr>
								<td>Parking</td>
								<td>{{ $property->parking->name }}</td>
							</tr>
						</tbody>
					</table>
				</section><!-- END .property-amenities -->
				<section class="property-manager">
					<span>Property Manager</span>
					<span class="address"> {{ $property->user->street }}, {{$property->user->town }}, {{ $property->user->county }}, {{ $property->user->postcode }}</span>
					<span>P: {{ $property->user->public_phone }}</span>
					<span>E: {{ $property->user->public_email }}</span>
					<a href="#faq" class="btn btn-orange"><i class="fa fa-envelope-o"></i> Contact</a>
				</section>
			</div>
		</div><!-- END .sidebar -->
		<section class="property-details">
			@if($property->created_at != $property->updated_at)
				<p>Edited: {{ $property->updated_at }}</p>
			@endif
			<section class="description">
				<h2>Description</h2>
				<p>{{ $property->description }}</p>
				<section class="property-manager-info"></section>
			</section><!-- END .description -->

			@if(count($features) >= 1)
			<section class="features">
				<h2 id="features">Additional Features</h2>
				<ul>
					@foreach($features as $feature)
						<li> {{ $feature->name }}</li>
					@endforeach
				</ul>
			</section><!-- END .features -->
			@endif

			<section class="faq">
				<h2 id="faq">Frequently Asked Questions</h2>
				@if(isset($questions))
					@foreach($questions as $question)
						<p><strong>Question:</strong> {{ $question->text }}</p>
						<p><strong>Answer:</strong> {{ $question->answer->text }}</p>
						<hr />
					@endforeach
				@else
					<p>No questions available.</p>
				@endif

				<p>Send a question to the property manager below.</p>

				{{ Former::open()->method('post')->action('/property/faq/') }}

					{{ Former::hidden('prop_id', $property->id) }}

					{{ Former::textarea('question')->label('') }}

					<input class="btn-large btn-primary btn" type="submit" value="Submit">
					
				{{ Former::close() }}
			</section><!-- END .faq -->

			<section class="location">
				<h2 id="location">Location</h2>
				<div id="map_canvas"></div>
			</section><!-- END .location -->

			<h2 id="photos">Photos</h2>

			<section class="photos grid gutters grid--full large-grid--1of2">
				@foreach ($property->upload as $image)
					<div class="grid-block">
				       	{{ HTML::image($image->media->url('medium').$image->media->filename) }}
					</div>
				@endforeach
			</section><!-- END .photos -->


		</section><!-- END .property-details -->

	</div><!-- END .container -->

@stop
