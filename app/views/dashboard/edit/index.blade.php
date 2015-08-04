<!-- app/views/dashboard/edit/index.blade.php -->

@extends('layouts.master')

@section('title', 'Edit Property')

@section('page-script')

{{ HTML::script('/assets/js/libs/jquery.sumoselect.min.js') }}

<script type="text/javascript">

    $(function() {
        
        $( "#datepicker" ).datepicker({ dateFormat: 'd/mm/y' });

        $( "#price-pcm" ).keyup(function(){
            var price = $(this).val() / 4.33;
            $( "#price-pw" ).val(Math.round(price));
        });

        $( "#price-pw" ).keyup(function(){
            var price = $(this).val() * 4.33;
            $( "#price-pcm" ).val(Math.round(price));
        });

        $('.selectBox').SumoSelect();
            
        $(".btn-more-filters").click(function(){
            $(".search-advanced").toggleClass("search-advanced-open");
        });

    });
</script>
@stop

@section('content')
    {{ Former::populate( $property ) }}
    {{ Former::populateField('price-pcm', $property->price) }}
    {{ Former::populateField('let-agreed', $property->let_agreed) }}


    <div class="container">
        <section class="form-property">
            <h1>Editing Property</h1>
            @if(Session::has('message'))
              <div class="flash success"><span>{{ Session::get('message') }}</span></div>
            @endif
            @include('dashboard.form')
        </div>
    </div>
    <script>
        var existingfiles = {{ $json }};
    </script>

@stop