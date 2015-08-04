<!-- app/views/dashboard/index.blade.php -->

@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-class', 'dashboard')

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
            <h1>Dashboard</h1>

            @if (isset($properties))
                <div class="properties properties-1of2">

                    
                    @foreach ($properties as $property)
                        @if($property->let_agreed == 1)
                        <div class="property property-let-agreed">
                        @else
                        <div class="property">
                        @endif
                          <div class="property-images">
                          @if($count = FaqQuestion::where('prop_id', $property->id)->where('read', 0)->count())
                            <div class="flash success"><span><a href="{{ URL::Route('faq.showQuestions', $property->id) }}"><i class="fa fa-envelope-o"></i> You have a new message</a></span></div>
                          @endif
                            <div class="slides">
                              @foreach ($property->upload as $image)
                                {{ HTML::image($image->media->url('thumb').$image->media->filename) }}
                              @endforeach
                              <div class="property-slide-nav"></div>
                            </div>
                            <span class="property-images-num">
                            @if(count($property->upload) == 1)
                                {{ count($property->upload) }} image
                            @else
                                {{ count($property->upload) }} images
                            @endif
                            </span>
                          </div><!-- END .slideshow-block -->
                          <div class="property-info">
                            <div class="left">
                                <span class="address">{{ $property->street }}, {{ $property->town }}, {{ $property->county }}</span>
                                <div class="amenities">
                                    <span>{{ $property->bedrooms }} <i class="fa fa-bed"></i></span>
                                </div><!-- END .amenities -->
                            </div>
                            <span class="price">£{{ (Session::get('price_format', 'pm') == 'pm' ? $property->price . ' PCM' : round($property->price / 4.33) . ' PW') }} </span>
                            <div class="button-group">
                                <a href="{{ URL::route('dashboard.edit', $property->id) }}" class="btn btn-large btn-grey"><i class="fa fa-edit"></i> Edit</a>
                                {{ Form::open(array('action' => array('PropertyController@destroy', $property->id), 'method' => 'delete')) }}
                                    <button type="submit" class="btn btn-large btn-warning"><i class="fa fa-trash"></i> Delete</button>
                                {{ Form::close() }}
                            </div>
                          </div><!-- END .property-info -->

                        </div><!-- END .property -->
                    @endforeach
                </div><!-- END .properties -->
            @else
                <p>No properties found</p>
            @endif
        </section>

        <section class="sidebar-nav">

          {{ HTML::linkRoute('dashboard.create', 'New Property', [], ['class' => 'btn btn-orange']) }}
          {{ HTML::linkRoute('favourites.index', 'View Favourites', [], ['class' => 'btn btn-orange']) }}
          {{ HTML::linkRoute('favourites.index', 'View Messages', [], ['class' => 'btn btn-orange']) }}

        </section>
    </div>

@stop