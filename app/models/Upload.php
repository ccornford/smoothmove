<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class Upload extends Eloquent implements StaplerableInterface {

    use EloquentTrait;

    /**
     * Fillable columns in the database table.
     *
     * @var array
    **/
    protected $fillable = ['media', 'user_id', 'prop_id'];

    public function __construct(array $attributes = array()) {
        //details the size of image uploads
        $this->hasAttachedFile('media', [
            'styles' => [
                'medium' => '900x600#',
                'thumb' => '350x200#'
            ],
            'url' => '/assets/uploads/:id_partition/:style/:filename'
        ]);

        parent::__construct($attributes);
    }

    /**
     * The database table used by the model.
     *
     * @var string
    **/
    protected $table = 'property_media';

    public function property()
    {
        return $this->belongsTo('Property', 'prop_id');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

        public function scopeUser($query)
    {

        return $query->where('user_id', '=', Sentry::getUser()->id);

    }

}
