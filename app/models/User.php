<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'password', 
        'phone', 
        'public_email', 
        'public_phone', 
        'street', 
        'town', 
        'county', 
        'postcode'
    ];

    public static $rules = [
        'first_name'            => 'required|min:2|max:25',
        'last_name'             => 'required|min:2|max:25',
        'email'                 => 'sometimes|required|email|min:5|unique:users',
        'password'              => 'required|min:6|max:20|confirmed|regex:^(?=.*[A-Z])(?=.*[0-9]).{8}^',
        'password_confirmation' => 'required|min:6|max:20',
        'phone'                 => 'required|max:20|min:6'
    ];

    public static $messages = [
        'password.regex' => 'Password must be atleast 8 characters in length including one uppercase letter and one number',
    ];

    public $errors;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');


    public function isValid($data)
    {

        $validation = Validator::make($data, static::$rules, static::$messages);

        if ($validation->passes())
        {
            return true;
        }

        $this->errors = $validation->errors();

        return false;

    }

    public function property()
    {
        return $this->hasMany('Property');
    }

    public function upload()
    {
        return $this->hasMany('Upload');
    }

    public function userFavourite()
    {
        return $this->hasMany('UserFavourite');
    }

}
