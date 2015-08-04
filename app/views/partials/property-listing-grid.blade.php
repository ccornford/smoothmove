
@foreach ($properties as $property)

    <div class="property" data-property-id="{{ $property->id }}">
        @if(strtotime($property->listing_date) >= strtotime('-24 hours'))
            <span class="property-recent">Recently Added</span>
        @endif

        @if($property->let_agreed == 1)
            <div class="property-images property-let-agreed">
        @else
            <div class="property-images">
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
                <div class="favourite">
                  @if(Sentry::check() && UserFavourite::where('prop_id', $property->id)->where('user_id', Sentry::getUser()->id)->first())
                        {{ Form::open(array('action' => array('UserFavouriteController@destroy', $property->id), 'method' => 'delete')) }}
                            <button type="submit" class="btn btn-star hint--bottom" data-hint="Unfavourite"><i class="fa fa-star"></i></button>
                        {{ Form::close() }}
                  @else
                        {{ Form::open(array('action' => array('UserFavouriteController@store', $property->id), 'method' => 'post')) }}
                            <button type="submit" class="btn btn-star hint--bottom" data-hint="Favourite"><i class="fa fa-star-o"></i></button>
                        {{ Form::close() }}
                    @endif
                </div><!-- END .favourite -->
                <span class="address">{{ $property->street }}, {{ $property->town }}, {{ $property->county }}</span>
                <div class="amenities">
                    <span>{{ $property->bedrooms }} <i class="fa fa-bed"></i></span>
                </div><!-- END .amenities -->
            </div>
            <span class="price">£{{ (Session::get('price_format', 'pm') == 'pm' ? $property->price . ' PCM' : round($property->price / 4.33) . ' PW') }} </span>
            <span class="description">
                {{ Str::limit($property->description, 200) }}
            </span>
            <div class="button-group">
                <a href="{{ URL::route('property.show', $property->id) }}" class="btn btn-grey"><i class="fa fa-eye"></i> View</a>
                <a href="{{ URL::route('property.show', $property->id) }}#faq" class="btn btn-orange"><i class="fa fa-envelope-o"></i> Contact</a>
            </div>
        </div><!-- END .property-info -->
    </div><!-- END .property -->
@endforeach


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