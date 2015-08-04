<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::resource('user', 'UserController');

Route::pattern('id', '[0-9]+');

// Static pages
Route::get('/', array(
    'uses'      => 'PageController@home',
    'as'        => 'page.home'
));

//Search
Route::get('/property/search/', array(
    'uses'      => 'PropertyController@search',
    'as'        => 'property.search'
));

Route::get('/property/search/{layout}/', array(
    'uses'      => 'PropertyController@layout',
    'as'        => 'property.layout'
));

Route::post('/property/search/order/', array(
    'uses'      => 'PropertyController@order',
    'as'        => 'property.order'
));

Route::get('/property/details/{id}/', array(
    'uses'      => 'PropertyController@show',
    'as'        => 'property.show'
));


//Register
Route::get('/register/', array(
    'before'    => 'logged_in',
    'uses'      => 'UserController@create',
    'as'        => 'register.show'
));

Route::post('/register/', array(
    'before'    => 'logged_in',
    'uses'      => 'UserController@store',
    'as'        => 'register.store'
));

Route::get('/forgot','LoginController@showForgotPassword');
  
Route::post('/forgot','LoginController@storeForgotPassword');
  
Route::get('/reset','LoginController@showNewPassword');
  
Route::post('/reset', array(
    'uses' => 'LoginController@storeNewPassword', 
    'as' => 'reset.store'
));

// login / logout
Route::get('/login/', array(
    'uses'  => 'LoginController@showLogin',
    'as'    => 'login.show'
));

Route::post('/login/','LoginController@doLogin');

Route::get('/logout/', array(
    'uses'  => 'LoginController@doLogout',
    'as'    => 'logout.do'
));


// User group
Route::group(array('before' => 'members_auth|hasAccess:user'), function()
{

    Route::post('/account/upgrade/', array(
        'uses'  => 'UserController@storeUpgrade',
        'as'    => 'account.storeUpgrade'
    ));

    Route::get('/account/', array(
        'uses'  => 'UserController@edit',
        'as'    => 'account.edit'
    ));


    Route::post('/account/', array(
        'uses'  => 'UserController@update',
        'as'    => 'account.update'
    ));

    Route::post('/account/newpassword', array(
        'uses'  => 'UserController@newPassword',
        'as'    => 'account.newpassword'
    ));

    Route::get('/account/messages', array(
        'uses'  => 'FAQController@showAnswers',
        'as'    => 'account.messages'
    ));

    //Property Favourites

    Route::get('/property/favourites/', array(
        'uses'  => 'UserFavouriteController@index',
        'as'    => 'favourites.index'
    ));

    Route::post('/property/favourites/?{id}', array(
        'before'    => 'csrf',
        'uses'      => 'UserFavouriteController@store',
        'as'        => 'favourites.store'
    ));

    Route::delete('/property/favourites/?{id}', array(
        'before'    => 'csrf',
        'uses'      => 'UserFavouriteController@destroy',
        'as'        => 'favourites.destroy'
    ));

    //Property FAQ's
    Route::post('/property/faq/', array(
        'before'    => 'csrf',
        'uses'      => 'FAQController@storeQuestion',
        'as'        => 'faq.storeQuestion'
    ));

});


// Property Managers group
Route::group(array('before' => 'members_auth|hasAccess:manager'), function()
{
    Route::get('/dashboard/', array(
        'uses'  => 'PropertyController@index',
        'as'    => 'dashboard.show'
    ));

    Route::get('/dashboard/new/', array(
        'uses'  => 'PropertyController@create',
        'as'    => 'dashboard.create'
    ));

    Route::post('/dashboard/new/', array(
        'before'    => 'csrf',
        'uses'      => 'PropertyController@store',
        'as'        => 'dashboard.store'
    ));

    Route::get('/dashboard/edit/{id}', array(
        'uses'  => 'PropertyController@edit',
        'as'    => 'dashboard.edit'
    ));

    Route::post('/dashboard/edit/{id}', array(
        'before'    => 'csrf',
        'uses'      => 'PropertyController@update',
        'as'        => 'dashboard.update'
    ));

    Route::get('/dashboard/edit/{id}/faq', array(
        'uses'  => 'FAQController@showQuestions',
        'as'    => 'faq.showQuestions'
    ));

    Route::post('/dashboard/edit/{id}/faq', array(
        'before'    => 'csrf',
        'uses'      => 'FAQController@storeAnswer',
        'as'        => 'faq.storeAnswer'
    ));

    Route::delete('/property/delete/{id}', array(
        'before'    => 'csrf',
        'uses'      => 'PropertyController@destroy',
        'as'        => 'property.destroy'
    ));

    Route::post('/upload/', array(
        'uses'  => 'UploadController@store',
        'as'    => 'upload.store'
    ));
    Route::delete('/upload/{id}', array(
        'uses'  => 'UploadController@destroy',
        'as'    => 'upload.destroy'
    ));
    
});