<?php

class PropertyFurnished extends Eloquent {

    protected $fillable = ['name'];

    protected $table = 'property_furnished';

    public function property()
    {
        return $this->belongsToMany('Property');
    }

}