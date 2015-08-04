<!-- app/views/property/search/index.blade.php -->

@extends('layouts.master')

@section('title', 'Search')

@section('page-class', 'search')

@section('page-script')
	{{ HTML::script('/assets/js/libs/jquery.sumoselect.min.js') }}
	{{ HTML::script('https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true') }}

	<script>
		$(document).ready(function () {
			$('.selectBox').SumoSelect();
			
			$(".btn-more-filters").click(function(){
				$(".search-advanced").toggleClass("search-advanced-open");
			});
     	});
     	$(document).ready(function () {

	     	var map_canvas = document.getElementById('map_canvas');

		    // Initialise the map
		    var map_options = {
                center: new google.maps.LatLng({{ $geocode->getLatitude() }}, {{ $geocode->getLongitude() }}),
		        zoom: 10,
		        mapTypeId: google.maps.MapTypeId.ROADMAP,
		        zoomControl: false,
	            disableDoubleClickZoom: true,
	            mapTypeControl: false,
	            scaleControl: false,
	            scrollwheel: true,
	            panControl: false,
	            streetViewControl: false,
	            draggable : false,
	            overviewMapControl: false,
	            overviewMapControlOptions: {
	                opened: false,
	            }
		    }
		    var map = new google.maps.Map(map_canvas, map_options);
		    var bounds = new google.maps.LatLngBounds();

		    // Put all locations into array
		    <?php foreach($properties as $property): ?>
		    	var myLatLng = new google.maps.LatLng({{ $property->latitude }}, {{ $property->longitude }}),
		        marker{{ $property->id }} = new google.maps.Marker({
		            position: myLatLng,
		            map: map,
		            icon: '/assets/images/icon-pin.png'
		        });
		        bounds.extend(myLatLng);
		    <?php endforeach ?>

		    map.fitBounds(bounds);
			zoomChangeBoundsListener = 
			google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
		        if (this.getZoom() > 12){
		            this.setZoom(12);
		        }
			});
			setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);
		});

	</script>
@stop

@section('content')

	{{ Former::populate( $input ) }}

	<div class="search-wrapper">
		<div class="container">

			{{ Former::open()->method('get')->class('form-inline') }}

			    {{ Former::text('s')->label('')->placeholder('e.g. NE1 or Newcastle')->class('quater')->autofocus() }}

			    {{ Former::select('type[]')->class('selectBox')->multiple()->label('')->fromQuery(PropertyType::all(), 'name', 'id') }}

			    {{ Former::select('min-price')->class('selectBox')->addGroupClass('price')->label('')->options(
			        [
			        	''	  => 'No Min',
						'100' => '£100 pcm',
						'200' => '£200 pcm',
						'300' => '£300 pcm',
						'400' => '£400 pcm',
						'500' => '£500 pcm',
						'600' => '£600 pcm',
						'700' => '£700 pcm',
						'800' => '£800 pcm',
						'900' => '£900 pcm',
			        ]
			    ) }}
				<span>to</span>
			    {{ Former::select('max-price')->class('selectBox')->addGroupClass('price')->label('')->options(
			        [
			        	''	  => 'No Max',
						'100' => '£100 pcm',
						'200' => '£200 pcm',
						'300' => '£300 pcm',
						'400' => '£400 pcm',
						'500' => '£500 pcm',
						'600' => '£600 pcm',
						'700' => '£700 pcm',
						'800' => '£800 pcm',
						'900' => '£900 pcm',
			        ]
			    ) }}
			    <span>per month</span>




			    <div class="form-actions">
			    	<a class="btn btn-more-filters">More Filters</a>
			    	<button class="btn btn-magnify" type="submit" >Search</button>
			    </div>


				<div class="search-advanced">
					
					<h3>Filter Results</h3>
					{{ Former::text('keyword')->label('')->placeholder('Keyword')->class('quater')->autofocus() }}

					{{ Former::select('garden[]')->class('selectBox')->label('Garden')->multiple('multiple')->fromQuery(PropertyGarden::all(), 'name', 'id') }}
				    {{ Former::select('parking[]')->class('selectBox')->label('Parking')->multiple('multiple')->fromQuery(PropertyParking::all(), 'name', 'id') }}
				    {{ Former::select('furnished[]')->class('selectBox')->label('Furnishings')->multiple('multiple')->fromQuery(PropertyFurnished::all(), 'name', 'id') }}

				    {{ Former::select('beds')->label('Bedrooms')->class('selectBox')->label('Bedrooms')->options(
				        [
				            '1' => '1+',
				            '2' => '2+',
				            '3' => '3+',
				            '4' => '4+',
				            '5' => '5+',
				            '6' => '6+',
				        ]
				    ) }}

				  	{{ Former::select('distance')->class('selectBox')->label('Distance')->options(
				        [
							'10' => '10 Miles',
							'15' => '15 Miles',
							'25' => '25 Miles',
							'50' => '50 Miles',
				        ]
				    ) }}

				    {{ Former::select('format')->class('selectBox')->label('')->select(Session::get('price_format', 'pm'))->options(
				        [
							'pm' => 'Price per month',
							'pw' => 'Price per week',
				        ]
				    ) }}
				    			   	
			    </div>

			{{ Former::close() }}
		</div><!-- END .container -->
	</div><!-- END .search-wrapper -->
	<div class="container">
	 	<h1>Search Results</h1>
		<div class="filter-options">
			{{ Former::populate( array('sort' => Session::get('order')) ) }}
			{{ Former::open()->method('POST')->action('/property/search/order/') }}
				{{ Former::select('sort')->label('')->setAttribute('onchange', 'this.form.submit()')->class('selectBox')->options(
			        [
						'most_recent' => 'Most recent',
						'highest_price' => 'Highest price',
						'lowest_price' => 'Lowest price',
			        ]
				) }}
			{{ Former::close() }}
			<span>
				<a href="{{ URL::Route('property.layout', 'grid') }}"><i class="fa fa-th"></i> Grid view</a> 
				<a href="{{ URL::Route('property.layout', 'list') }}"><i class="fa fa-list"></i> List view</a>
			</span>
			<span class="resultcount">{{ count($properties) }} {{ count($properties) == 1 ? 'result' : 'results' }}</span>
		</div>

		<section class="property-listing {{ Session::get('layout', 'grid') }}">
			@if (count($properties) > 0)
	    		<div class="properties properties-1of2">

		    		@include('partials.property-listing-grid')

		    	</div><!-- END .properties -->
			@else
			    <span>No properties were found.</span>
			@endif

		</section><!-- END .property-listing -->
		<section class="property-map">
			<div id="map_canvas" @if($properties->count() < 1) style="display:none;" @endif></div>
		</section><!-- END .property-map -->
	</div><!-- END .container -->

@stop
