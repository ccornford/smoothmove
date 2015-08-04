<?php

class FaqQuestion extends Eloquent {

    protected $fillable = ['text', 'public'];

    protected $table = 'property_faq_questions';

    public function property()
    {
        return $this->belongsTo('Property', 'prop_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function answer()
    {
        return $this->hasOne('FaqAnswer', 'question_id');
    }

}
