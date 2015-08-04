<?php

class UserFavouriteController extends \BaseController {

    /**
     * Display a listing of the favourites.
     *
     * @return Response
     */
    public function index()
    {

        $properties = Property::whereIn('properties.id', function($query)
        {
            $query->select('users_favourites.prop_id')
                ->from('users_favourites')
                ->whereRaw('users_favourites.user_id = ' . Sentry::getUser()->id);
        })->get();

        return View::make('property.favourites.index', compact('properties'));
        
    }


    /**
     * Store a newly created faviourite in DB.
     * 
     * @param  int $prop_id
     * @return Response
     */
    public function store($prop_id)
    {

        $favourite = new UserFavourite;

        $favourite->user_id = Sentry::getUser()->id;
        $favourite->prop_id = $prop_id;

        $favourite->save();


        return Redirect::back();

    }

    /**
     * Remove the specified favourite from DB.
     *
     * @param  int  $prop_id
     * @return Response
     */
    public function destroy($prop_id)
    {
        
        UserFavourite::where('prop_id', $prop_id)
            ->where('user_id', Sentry::getUser()->id)->delete();
        
        return Redirect::back();

    }

}

