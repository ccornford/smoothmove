<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class PropertyFeature extends Eloquent implements StaplerableInterface {

    use EloquentTrait;

    /**
     * Fillable columns in the database table.
     *
     * @var array
    **/
    protected $fillable = ['name', 'prop_id'];

    public function __construct() {
   
    }

    /**
     * The database table used by the model.
     *
     * @var string
    **/
    protected $table = 'property_features';

    public function property()
    {
        return $this->belongsTo('Property', 'prop_id');
    }

}
