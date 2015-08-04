<!-- app/views/dashboard/faq.blade.php -->

@extends('layouts.master')

@section('title', 'Property FAQ')

@section('page-script')

@stop

@section('content')
<div class="container">
    <h1>Frequently Asked Questions</h1>

    @if(Session::has('message'))
       <div class="flash success"><span>{{ Session::get('message') }}</span></div>
    @endif

    @if(count($questions) > 0)

        @foreach($questions as $question)
            <div class="question @if($question->read == 0) unread @endif">
                <p><strong>Question:</strong> {{ $question->text }}</p>
                @if(isset($question->answer->text))

                    <p><strong>Answer:</strong> {{ $question->answer->text }}</p>

                    {{ Former::populate( $question ) }}

                    {{ Former::open()->method('post') }}  

                        {{ Former::hidden('question_id', $question->id) }}

                        {{ Former::checkbox('public')->label('')->text('Display to the public?') }}

                        {{ Former::actions()->large_primary_submit('Submit') }}

                    {{ Former::close() }}

                @else

                    {{ Former::open()->method('post') }}  

                        {{ Former::hidden('question_id', $question->id) }}

                        {{ Former::textarea('answer')->label('') }}

                        {{ Former::checkbox('public')->label('')->text('Display to the public?') }}

                        {{ Former::actions()->large_primary_submit('Submit') }}

                    {{ Former::close() }}

                @endif
            </div>
            <hr />
        @endforeach

    @else
        <p>No questions have been sent.</p>
    @endif
</div>

@stop