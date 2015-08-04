<?php

class PropertyController extends \BaseController {

    protected $property;

    public function __construct(Property $property)
    {

        $this->property = $property;

        $filtered = array('edit', 'update', 'destroy'); //controllers to be checked
        $id = Route::current()->getParameter('id');

        $this->beforeFilter('ownerAuth:' . 'Property-'.$id, array('only' => $filtered));

    }

    /**
     * Display a listing of properties for the dashboard page.
     *
     * @return Response
     */
    public function index()
    {
        $properties = Property::where('user_id', Sentry::getUser()->id)->get(); //get only properties the user has made

        return View::make('dashboard.index', compact('properties'));

    }


    /**
     * Show the form for creating a new property.
     *
     * @return Response
     */
    public function create()
    {

        return View::make('dashboard.new.index');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        $input = Input::all(); //assign all form input to a variable

        $this->property->isValid($input, property::$rules); //validate form data

        if($this->property->errors) { //check if any errors have been stored

            return Redirect::back() //redirect back to form page with input and any errors
            ->withInput()
            ->withErrors($this->property->errors);

        }

        try {
            $geocode = Geocoder::geocode( $input['postcode'] );
            // The GoogleMapsProvider will return a result
        } catch (\Exception $e) {
            // No exception will be thrown here
            echo $e->getMessage();
        }


        $property = new Property;

        $property->description  = $input['description'];
        $property->price        = $input['price-pcm'];
        $property->bedrooms     = $input['bedrooms'];
        $property->bathrooms    = $input['bathrooms'];

        $property->street       = $input['street'];
        $property->town         = $input['town'];
        $property->county       = $input['county'];
        $property->postcode     = $input['postcode'];

        $property->longitude    = $geocode->getLongitude();
        $property->latitude     = $geocode->getLatitude();

        $property->listing_date = $input['listing-date'];
        $property->type_id      = $input['type'];
        $property->furnished_id = $input['furnished'];
        $property->garden_id    = $input['garden'];
        $property->parking_id   = $input['parking'];

        $property->let_agreed   = (!empty($input['let-agreed']) ? $input['let-agreed'] : 0 );

        $property->user_id = Sentry::getUser()->id; //add user id

        $property->save();

        if(isset($input['features'])) {

            foreach($input['features'] as $name) { //each feature add new record
                $feature = new PropertyFeature;

                $feature->name = $name;
                $feature->prop_id = $property->id;
                $feature->save();
            }

        }

        foreach($input['images'] as $image) { //each image add new record

            $upload = Upload::find($image);

            $upload->prop_id = $property->id;

            $upload->save();

        }
        //redirect to new property with message
        return Redirect::route('property.show', $property->id)->withMessage('Congratulations! Your property has been successfully created!');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //get property and related features
        $property = Property::find($id);
        $features = PropertyFeature::where('prop_id', $id)->get();

        if(Sentry::check()) { //check if logged in to check logged in only elements
            $fav = UserFavourite::where('user_id', Sentry::getUser()->id)->where('prop_id', $id)->first();
            
            $questions = FaqQuestion::where('prop_id', $id)
                ->where(function($query) {
                    $query->where('public', 1)
                        ->orwhere('user_id', Sentry::getUser()->id);
                })->has('answer')
                ->limit(5)
                ->get();

        } else {

            $questions = FaqQuestion::where('prop_id', $id)
                ->where('public', 1)
                ->limit(5)
                ->get();

        }

        $property->description = nl2br($property->description); //fix formatting to display new lines

        return View::make('property.details.index', compact('property', 'fav', 'questions', 'features'));
    }


    /**
     * Show the form for editing the specified property.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //get property, related images and features
        $property = Property::find($id);
        $images = $property->upload;
        $features = $property->feature;

        $files = array();

        foreach ($images as $image) {

            if (isset($image)) {

                $thumburl = URL::asset($image->media->url('thumb').$image->media->filename);
                $fullsizeurl = URL::asset($image->media->url().$image->media->filename);

                // this creates the response structure for jquery file upload
                $success = new stdClass();
                $success->name          = $image->media->originalFilename();
                $success->size          = $image->media->size();
                $success->url           = $fullsizeurl;
                $success->thumbnailUrl  = $thumburl;
                $success->deleteUrl     = action('UploadController@destroy', $image->id);
                $success->deleteType    = 'DELETE';
                $success->fileID        = $image->id;

                $files[] = $success;
            }
        }

        $json = json_encode(array('files' => $files));

        return View::make('dashboard.edit.index', compact('property', 'json', 'features'));

    }


    /**
     * Update the specified property in DB.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::all(); //assign all form input to a variable

        $this->property->isValid($input, property::$rules); //validate form data

        if($this->property->errors) { //check if any errors have been stored

            return Redirect::back() //redirect back to form page with input and any errors
            ->withInput()
            ->withErrors($this->property->errors);

        }

        try {
            $geocode = Geocoder::geocode( $input['postcode'] );
            // The GoogleMapsProvider will return a result
        } catch (\Exception $e) {
            // No exception will be thrown here
            echo $e->getMessage();
        }

        $property = Property::find($id);

        $property->description  = $input['description'];
        $property->price        = $input['price-pcm'];
        $property->bedrooms     = $input['bedrooms'];
        $property->bathrooms    = $input['bathrooms'];

        $property->street       = $input['street'];
        $property->town         = $input['town'];
        $property->county       = $input['county'];
        $property->postcode     = $input['postcode'];

        $property->longitude    = $geocode->getLongitude();
        $property->latitude     = $geocode->getLatitude();

        $property->listing_date = $input['listing-date'];
        $property->type_id      = $input['type'];
        $property->furnished_id = $input['furnished'];
        $property->garden_id    = $input['garden'];
        $property->parking_id   = $input['parking'];

        $property->let_agreed   = (isset($input['let-agreed']) == 0 ? 0 : 1 );

        $property->save();

        PropertyFeature::where('prop_id', $property->id)->delete();

        if(isset($input['features'])) {

            foreach($input['features'] as $name) {

                $feature = PropertyFeature::where('name', $name)->where('prop_id', $property->id);
                
                if(count($feature) !== 1) { //if feature isnt in DB for this property add it
        
                    $feature = new PropertyFeature;

                    $feature->name = $name;
                    $feature->prop_id = $property->id;
                    $feature->save();
        
                }
                
            }
        
        }
        
        foreach($input['images'] as $image) {

            $upload = Upload::find($image);
            if($upload->prop_id !== $property->id) { //if upload isnt in DB for this property add it
                $upload->prop_id = $property->id;
                $upload->save();
            }

        }

        return Redirect::back() //redirect back with message and input
            ->withInput()
            ->withMessage('Property was successfully edited!');

    }


    /**
     * Remove the specified property from DB.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Property::where('id', $id)->delete();

        $questions = FaqQuestion::where('prop_id', $id)->delete();
        
        return Redirect::back();    
    }


    /**
     * Get list of properties related to criteria.
     *
     * @return Response
     */
    public function search()
    {
        $input = Input::all(); //get all input data
        Input::flash(); //flast to session to fill form


        if(!empty($input)) {

            if(isset($input['format'])) {
                Session::put('price_format', $input['format']);
            }
            try {
                $geocode = Geocoder::geocode( $input['s'] );
                // The GoogleMapsProvider will return a result
            } catch (\Exception $e) {
                // No exception will be thrown here
                echo $e->getMessage();
            }

            $distance = (Input::has('distance') ? Input::get('distance') : 25);

            $properties = Property::select(
                'properties.*',
                'property_types.name as type',
                'property_furnished.name as furnished',
                'property_parking.name as parking',
                'property_garden.name as garden',
                DB::raw(
                    '( 3959 * acos(
                            cos( radians('.$geocode->getLatitude().') ) *
                            cos( radians( properties.latitude ) ) *
                            cos( radians( properties.longitude ) - radians('.$geocode->getLongitude().') )+
                            sin( radians('.$geocode->getLatitude().') ) * sin( radians( properties.latitude ) )
                        )
                    )
                    AS distance'
                )
            )
            ->join(
                'property_types',
                'properties.type_id', '=', 'property_types.id'
            )
            ->join(
                'property_furnished',
                'properties.furnished_id', '=', 'property_furnished.id'
            )
            ->join(
                'property_garden',
                'properties.garden_id', '=', 'property_garden.id'
            )
            ->join(
                'property_parking',
                'properties.parking_id', '=', 'property_parking.id'
            )->whereRaw('properties.listing_date < NOW()') //listing date must be before todays date/time
            ->keyword() //like name
            ->type() //type
            ->furnished() //furnished
            ->parking() //parking
            ->garden() //garden
            ->beds()  //min beds
            ->price() //price between
            ->having('distance', '<', $distance)//distance to location
            ->sort()  //order by
            ->get();
        
            return View::make('property.search.index', compact('input', 'properties', 'geocode')); //return to search page with properties

        }

        return View::make('page.home');

    }


    public function layout() 
    {

        $layout = Route::current()->getParameter('layout');

        Session::put('layout', $layout);


        return Redirect::back(); //redirect back with message and input

    }

    public function order() 
    {

        $input = Input::all();

        Session::put('order', $input['sort']);

        return Redirect::back(); //redirect back with message and input

    }

}

