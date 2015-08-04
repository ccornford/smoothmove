<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/* Custom Filters */
Route::filter('members_auth', function()
{
    //If already logged in go to dashboard or else login
    if(!Sentry::check()){
        return Redirect::to('/login');
    }
});

Route::filter('logged_in', function()
{
    //If already logged in go to dashboard or else login
    if(Sentry::check()){
        return Redirect::to('/dashboard');
    }
});


Route::filter('hasAccess', function($route, $request, $value)
{
    try
    {
      $user = Sentry::getUser();

      if( ! $user->hasAccess($value))
      {
          return Redirect::route('login.show')->withErrors(array(Lang::get('user.noaccess')));
      }
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
        return Redirect::route('login.show')->withErrors(array(Lang::get('user.notfound')));
    }

});

/*
|--------------------------------------------------------------------------
| Owner Access Auth Filter
|--------------------------------------------------------------------------
| Splits model and id from $value to check if the user is the owner
| of the object thus can edit/delete.
| 
| @param  string  $value
| @return Response
|
*/

Route::filter('ownerAuth', function($route, $request, $value)
{

	$array = explode('-', $value); // use - for delimiter

	$object = $array[0]::findOrFail($array[1]);
	$user = Sentry::getUser();

	if($object->user_id !== $user->id) {
		App::abort(403, 'Unauthorized action.');
	}

});

/*
|--------------------------------------------------------------------------
| Message alerts composer
|--------------------------------------------------------------------------
| Every page that uses layouts.master view will go through this composer checking 
| if the user has unread messages
| 
| @param  $view
| @return Response
|
*/
View::composer('layouts.master', function($view) {

	if(Sentry::check()) {
		$countQuestions = FaqQuestion::whereHas('property', function($query) {
			$query->where('user_id', Sentry::getUser()->id);
		})->where('read', 0)->count();

		$countAnswers = FaqAnswer::whereHas('question', function($query) {
			$query->where('user_id', Sentry::getUser()->id);
		})->where('read', 0)->count();

	    $view->with(compact('countAnswers', 'countQuestions'));
	}
});
