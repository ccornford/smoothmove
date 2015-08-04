<?php

class FaqAnswer extends Eloquent {

    protected $fillable = ['text'];

    protected $table = 'property_faq_answers';

    public function question()
    {
        return $this->belongsTo('FaqQuestion', 'question_id');
    }
    
}