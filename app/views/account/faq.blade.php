<!-- app/views/dashboard/faq.blade.php -->

@extends('layouts.master')

@section('title', 'Property FAQ')

@section('page-script')

<script type="text/javascript">

    $(function() {
        
    });
    
</script>
@stop

@section('content')
<div class="container">
    <h1>Messages</h1>

    @if(Session::has('message'))
       <div class="flash success"><span>{{ Session::get('message') }}</span></div>
    @endif
    @foreach($questions as $question)
        <div class="question @if($question->read == 0) unread @endif">
            <h2>
                <a href="{{ URL::Route('property.show', $question->property->id) }}">
                {{ $question->property->street }}, {{ $question->property->town }}, {{ $question->property->county }} - {{ $question->property->bedrooms }} @if($question->property->bedrooms != 1) Bedrooms @else Bedroom @endif {{ $question->property->type->name }}
                </a>
            </h2>
            <p><strong>Question:</strong> {{ $question->text }}</p>
            @if(isset($question->answer->text))
                <p><strong>Answer:</strong> {{ $question->answer->text }}</p>
            @endif
        </div>
        <hr />
    @endforeach
</div>

@stop