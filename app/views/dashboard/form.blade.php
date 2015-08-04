<!-- app/views/dashboard/form.blade.php -->
<!-- File Upload Source: http://peterjolson.com/using-laravel-and-jquery-file-upload/ -->

{{ Former::open_for_files()->method('post') }}
    <section class="property-form">
    
        <h2>Address</h2>

        {{ Former::text('street')->label('Street') }}

        {{ Former::text('town')->label('Town/City') }}
        
        {{ Former::text('county')->label('County') }}
        
        {{ Former::text('postcode')->label('Postcode') }}
        
        <h2>Property Details</h2>

        <div class="form-group half">
            {{ Former::text('price-pcm')->id('price-pcm')->label('Price PCM') }}
            {{ Former::text('price-pw')->id('price-pw')->label('Price PW') }}
        </div>
        
        {{ Former::textarea('description')->rows(10)->columns(20) }}

        {{ Former::text('additional_features')->id('add-feature') }}
        <div id="feature-display" style="border: 1px solid #DDD; height: 100px; padding: 5px;">
            @if(isset($features) )
                @foreach($features as $feature)
            
                    <span class="feature" data-feature-id="{{ $feature->id }}">
                        <i class="magnify fa fa-times-circle"></i>
                        <input name="features[]" type="hidden" value="{{ $feature->name }}">
                        {{ $feature->name }}
                    </span>
            
                @endforeach
            @endif
        </div>

        <div class="from-group half">
            {{ Former::select('bedrooms')->class('selectBox')->options(
                [
                    '1' => '1+',
                    '2' => '2+',
                    '3' => '3+',
                    '4' => '4+',
                    '5' => '5+',
                    '6' => '6+'
                ]
            ) }}
            {{ Former::select('bathrooms')->class('selectBox')->options(
                [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5+'
                ]
            ) }}
        </div>
        
        <div class="from-group half">
            {{ Former::select('type')->class('selectBox')->fromQuery(PropertyType::all(), 'name', 'id') }}
            {{ Former::select('garden')->class('selectBox')->fromQuery(PropertyGarden::all(), 'name', 'id') }}
        </div>
        
        <div class="from-group half">
            {{ Former::select('parking')->class('selectBox')->fromQuery(PropertyParking::all(), 'name', 'id') }}
            {{ Former::select('furnished')->class('selectBox')->fromQuery(PropertyFurnished::all(), 'name', 'id') }}
        </div>

        {{ Former::date('listing-date')->value(date('Y-m-d')) }}
        
        {{ Former::checkbox('let-agreed')->label('')->text('Let Agreed') }}
    </section><!-- END .property-form -->

    @if(isset($property->id))
    <section class="sidebar-nav">
        <a href="{{ URL::Route('property.show', $property->id) }}" class="btn btn-orange">View Property</a>
        <a href="{{ URL::Route('faq.showQuestions', $property->id) }}" class="btn btn-orange">View Messages</a>
    </section><!-- END .sidebar-nav -->
    @endif

    <div class="form-group file-upload">
        <label for="" class="control-label col-lg-2 col-sm-4">Logo Upload</label>
        <div class="col-lg-10 col-sm-8">
            @include('partials.upload-form')
            @if(Former::getErrors('images')) <span class="error">{{ Former::getErrors('images') }}</span> @endif
        </div>
    </div><!-- END .file-upload -->
    
    {{ Former::actions()->large_primary_submit('Submit') }}

{{ Former::close() }}

<script>
    $(document).ready(function(){
        addFeature();
        removeFeature();
    });

    //When a skill is clicked put it in the chosen skills box
    function addFeature() {

        $("input#add-feature").keyup(function(e) {
            if (!e) e = window.event;
            var keyCode = e.keyCode || e.which;
            if (keyCode == '13'){
              // Enter pressed
                var name = $(this).val();
                $('#feature-display').append('<span class="feature"><i class="magnify fa fa-times-circle"></i>'+name+'<input name="features[]" value="'+name+'" type="hidden"></span>');
                $(this).removeAttr('value');
                removeFeature();
            }
        });
    }

    //If the user clicks the x remove the skill
    function removeFeature() {
        $("#feature-display > span.feature").click(function() {
            $(this).remove();
        });
    }
</script>
