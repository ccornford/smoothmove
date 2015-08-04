<?php

class Property extends Eloquent {

    protected $fillable = [
        'description',
        'price',
        'bedrooms',
        'bathrooms',
        'street',
        'town',
        'county',
        'postcode',
        'longitude',
        'latitude',
        'listing_date',
        'type_id',
        'garden_id',
        'parking_id',
        'furnished_id',
        'let_agreed',
        'view_count'
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'properties';

    public static $rules = 
    [
        'price-pcm'     => 'required|numeric',
        'bedrooms'      => 'required|numeric',
        'bathrooms'     => 'required|numeric',
        'street'        => 'required|min:2',
        'town'          => 'required|min:3|max:25',
        'county'        => 'required|min:3',
        'postcode'      => 'required|min:6|max:9',
        'description'   => 'required',
        'listing-date'  => 'required|date',
        'type'          => 'required|exists:property_types,id',
        'furnished'     => 'required|numeric|exists:property_furnished,id',
        'garden'        => 'required|numeric|exists:property_garden,id',
        'parking'       => 'required|numeric|exists:property_parking,id',
        'images'        => 'required'
    ];

    public $errors;

    public function isValid($data, $rules)
    {

        $validation = Validator::make($data, $rules);

        if ($validation->passes())
        {
            return true;
        }

        $this->errors = $validation->errors();

        return false;

    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function type()
    {
        return $this->belongsTo('PropertyType', 'type_id');
    }

    public function parking()
    {
        return $this->belongsTo('PropertyParking');
    }

    public function garden()
    {
        return $this->belongsTo('PropertyGarden');
    }

    public function furnished()
    {
        return $this->belongsTo('PropertyFurnished');
    }

    public function upload()
    {
        return $this->hasMany('Upload', 'prop_id');
    }

    public function feature()
    {
        return $this->hasMany('PropertyFeature', 'prop_id');
    }

    public function userFavourite()
    {
        return $this->hasMany('UserFavourite', 'prop_id');
    }

    public function question()
    {
        return $this->hasMany('FaqQuestion', 'prop_id');
    }

    public function scopeKeyword($query)
    {

        if (Input::has('keyword'))
        {
            return $query->where('properties.description', 'LIKE', '%'.Input::get('keyword').'%');
        }

        return $query;
    }

    public function scopeType($query)
    {
        if (Input::has('type') && count(Input::get('type')) == 1)
        {
            return $query->where('property_types.id', '=', Input::get('type'));
        } 
        elseif(Input::has('type')) 
        {
            return $query->whereIn('property_types.id', Input::get('type'));
        }

        return $query;
    }

    public function scopeFurnished($query)
    {
        if (Input::has('furnished') && count(Input::get('furnished')) == 1)
        {
            return $query->where('property_furnished.id', '=', Input::get('furnished'));
        } 
        elseif(Input::has('furnished')) 
        {
            return $query->whereIn('property_furnished.id', Input::get('furnished'));
        }

        return $query;
    }

    public function scopeGarden($query)
    {
        if (Input::has('garden') && count(Input::get('garden')) == 1)
        {
            return $query->where('property_garden.id', '=', Input::get('garden'));
        } 
        elseif(Input::has('garden')) 
        {
            return $query->whereIn('property_garden.id', Input::get('garden'));
        }

        return $query;
    }

    public function scopeParking($query)
    {
        if (Input::has('parking') && count(Input::get('parking')) == 1)
        {
            return $query->where('property_parking.id', '=', Input::get('parking'));
        } 
        elseif(Input::has('parking')) 
        {
            return $query->whereIn('property_parking.id', Input::get('parking'));
        }

        return $query;
    }

    public function scopeBeds($query)
    {

        if (Input::has('beds'))
        {
            return $query->where('properties.bedrooms', '>=', (Input::get('beds')));
        }

        return $query;
    }

    public function scopePrice($query)
    {
        

        $min_price = (!empty(Input::get('min-price')) ? Input::get('min-price') : 0);
        $max_price = (!empty(Input::get('max-price')) ? Input::get('max-price') : 9999);

        return $query->whereBetween('properties.price', [$min_price, $max_price]);

    }

    public function scopeSort($query)
    {
        if ($sort = Session::get('order', 'most_recent'))
        {
            switch($sort) {
                case 'most_recent':
                    return $query->orderBy('properties.created_at', 'desc');
                    break;
                case 'highest_price':
                    return $query->orderBy('properties.price', 'desc')->orderBy('properties.created_at', 'desc');
                    break;
                case 'lowest_price':
                    return $query->orderBy('properties.price', 'asc')->orderBy('properties.created_at', 'desc');
                    break;
            }
        }

        return $query->orderBy('properties.created_at', 'desc');
    }
}

