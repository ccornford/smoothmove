<?php

class PropertyParking extends Eloquent {

    protected $fillable = ['name'];

    protected $table = 'property_parking';

    public function property()
    {
        return $this->belongsToMany('Property', 'parking_id');
    }

}
