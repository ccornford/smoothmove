<?php

class UserFavourite extends Eloquent {

    protected $fillable = ['user_id', 'prop_id'];

    protected $table = 'users_favourites';

    public function property()
    {
        return $this->belongsTo('Property', 'prop_id');
    }
    
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

}
