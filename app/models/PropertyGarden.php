<?php

class PropertyGarden extends Eloquent {

    protected $fillable = ['name'];

    protected $table = 'property_garden';

    public function property()
    {
        return $this->belongsToMany('Property', 'garden_id');
    }

}
