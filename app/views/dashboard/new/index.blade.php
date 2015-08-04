<!-- app/views/dashboard/new/index.blade.php -->

@extends('layouts.master')

@section('title', 'New Property')

@section('page-script')
{{ HTML::script('/assets/js/libs/jquery.sumoselect.min.js') }}

<script type="text/javascript">

    $(function() {

        $( "#price-pcm" ).keyup(function(){
            var price = $(this).val() / 4.33;
            $( "#price-pw" ).val(Math.round(price));
        });

        $( "#price-pw" ).keyup(function(){
            var price = $(this).val() * 4.33;
            $( "#price-pcm" ).val(Math.round(price));
        });

        $('.selectBox').SumoSelect();
        
    });
</script>
@stop

@section('content')

    <div class="form-property">
        <h1>Create property</h1>
        @include('dashboard.form')
    </div>
@stop
