<?php

class PropertyType extends Eloquent {

    protected $fillable = ['name'];

    protected $table = 'property_types';

    public function property()
    {
        return $this->hasMany('Property', 'type_id');
    }

}
