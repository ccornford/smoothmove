<!-- app/views/property/favourites/index.blade.php -->

@extends('layouts.master')

@section('title', 'Favourites')

@section('page-script')

	{{ HTML::script('assets/js/libs/slick-slider/slick.min.js') }}

	<script type="text/javascript">
		$(function() {
			$('.slides').each(function(idx, item){
				var carouselId = "carousel" + idx;
				this.id = carouselId;

				$(this).slick({
					infinite: true,
					speed: 500,
					fade: true,
					cssEase: 'linear',
					arrows: true,
					slide: "#" + carouselId + " img",
					appendArrows: "#" + carouselId + " .property-slide-nav"
				});

				//Only show nav buttons when image is hovered over.
				$(this).hover(function(){
					$(this).children('.property-slide-nav').fadeIn();
				},
				function(){
					$(this).children('.property-slide-nav').fadeOut();
				});
			});
		});
	</script>

@stop

@section('content')
<div class="container">
    <section class="property-listing">
		@if (isset($properties))
    		<div class="properties properties-1of3">

	    		@include('partials.property-listing-grid')

	    	</div><!-- END .properties -->
		@else
		    <p>No properties matching your criteria were found.</p>
		@endif
	</section><!-- END .property-listing -->
</div>
@stop


