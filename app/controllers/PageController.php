<?php

class PageController extends BaseController {

    public function home()
    {
    	$properties = Property::with('upload')->orderBy('created_at', 'desc')->take(3)->get();

        return View::make('pages.home', compact('properties'));

    }

}
