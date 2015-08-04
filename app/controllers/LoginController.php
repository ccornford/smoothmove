<?php

class LoginController extends \BaseController {

    public function showLogin()
    {

        if(Sentry::check()) {
            return Redirect::to('/');
        }
        //show the form
        return View::make('login.index');

    }

    public function doLogin()
    {
        //input validation rules
        $rules = array(
            'email'     => 'required|email',
            'password'  => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) {

            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));

        } else {

            $userdata = array(
                'email'     => Input::get('email'),
                'password'  => Input::get('password')
            );

            if(Auth::attempt($userdata)) {

                try {
                    //Try to authenticate user
                    $user = Sentry::getUserProvider()->findByLogin($userdata['email']);

                    $throttle = Sentry::getThrottleProvider()->findByUserId($user->id);

                    $throttle->check();

                    //For now auto activate users
                    $user = Sentry::authenticate($userdata, false);

                    //At this point we may get many exceptions lets handle all user management and throttle exceptions
                } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                    Session::flash('error_msg', 'Login field is required.');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
                    Session::flash('error_msg', 'Password field is required.');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
                    Session::flash('error_msg', 'Wrong password, try again.');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    Session::flash('error_msg', 'User was not found.');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                    Session::flash('error_msg', 'User is not activated.');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                    Session::flash('error_msg', 'User is suspended ');
                    return Redirect::to('/login');
                } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                    Session::flash('error_msg', 'User is banned.');
                    return Redirect::to('/login');
                }

                Session::flash('success_msg', 'Loggedin Successfully');
                if($user->hasaccess('manager')) {
                    return Redirect::to('dashboard');
                } else {
                    return Redirect::to('/');
                }

            } else {
                //return to login page with an error
                return Redirect::to('login')
                    ->withErrors('Incorrect username or password')
                    ->withInput(Input::except('password'));
            }
        }
    }

    public function doLogout()
    {

        Sentry::logout(); //logout user and redirect to login
        return Redirect::to('login');

    }

    public function showForgotPassword() 
    {
        return View::make('login.forgotpassword');
    }

    public function storeForgotpassword() 
    {

        if (Input::has('email')) {
  
            $email = Input::get('email');
  
            try {
                // Find the user using the user email address
                $user = Sentry::findUserByLogin($email);
  
                // Get the password reset code
                $resetCode = $user -> getResetPasswordCode();
  
                Mail::send("emails.resetpassword", array("email" => $email, "resetCode" => $resetCode), function($message) use ($email, $resetCode) {
                    $message -> to($email) -> subject('Follow the link to reset your password');
                });
  
                Session::flash('success_msg', 'We have sent a link to your email account please follow that to reset your password');
                return Redirect::to('/forgot');
  
                // Now you can send this code to your user via email for example.
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                Session::flash('error_msg', 'User not found');
                return Redirect::to('/forgot');
            }
        } else {
            Session::flash('error_msg', 'User not found');
            return Redirect::to('/forgot');
        }
  
    }

    public function showNewPassword() 
    {
        if (Input::has('email') && Input::has('resetcode')) {
  
            try {
                // Find the user using the user id
                $user = Sentry::findUserByLogin(Input::get('email'));
  
                // Check if the reset password code is valid
                if ($user -> checkResetPasswordCode(Input::get('resetcode'))) {
                    return View::make('login.newpassword');
  
                } else {
                    Session::flash('error_msg', 'Invalid request . Please enter email to reset your password');
                    return Redirect::to('/forgot');
                }
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                Session::flash('error_msg', 'User not found');
                return Redirect::to('/forgot');
            }
        } else {
            Session::flash('error_msg', 'Invalid request . Please enter email to reset your password');
            return Redirect::to('/forgot');
        }
    }

    public function storeNewPassword() 
    {
        //Validator to check password ,password confirmation
        $input = array(
            'password' => Input::get('password'), 
            'password_confirmation' => Input::get('password_confirmation')
        );
  
        $rules = array(
            'password' => 'required|min:4|max:32|confirmed', 
            'password_confirmation' => 'required|min:4|max:32'
        );
  
        $validator = Validator::make($input, $rules, User::$messages);
  
        if ($validator -> passes()) {
            if (Input::has('email') && Input::has('resetcode')) {
  
                try {
                    // Find the user using the user id
                    $user = Sentry::findUserByLogin(Input::get('email'));
  
                    // Check if the reset password code is valid
                    if ($user -> checkResetPasswordCode(Input::get('resetcode'))) {
                        // Attempt to reset the user password
                        if ($user -> attemptResetPassword(Input::get('resetcode'), Input::get('password'))) {
                            Session::flash('success_msg', 'Password changed successfully . Please login below');
                            return Redirect::to('/login');
                        } else {
                            Session::flash('error_msg', 'Unable to reset password . Please try again');
                            return Redirect::to('/forgot');
                        }
                    } else {
                        Session::flash('error_msg', 'Unable to reset password . Please try again');
                        return Redirect::to('/forgot');
                    }
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    Session::flash('error_msg', 'User not found');
                    return Redirect::to('/forgot');
                }
            } else {
                Session::flash('error_msg', 'Invalid request . Please enter email to reset your password');
                return Redirect::to('/forgot');
            }
        } else {
            return Redirect::to('/reset?email=' . Input::get('email') . '&resetcode=' . Input::get('resetcode')) -> withErrors($v) -> withInput();
        }
    }

}

