<?php

class UserController extends \BaseController {

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Store a newly created user in storage.
     *
     * @return Response
     */
    public function store()
    {

        $data = Input::all();

        if( !$this->user->isValid($data) ) //validate against rules in model using a function isValid() in the model also.
        {

            return Redirect::back()
                ->withInput()
                ->withErrors($this->user->errors); //return with errors

        } else {

            try {

                //Pre activate user
                $user = Sentry::register(array(
                    'email'         => $data['email'],
                    'password'      => $data['password'],
                    'first_name'    => $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'phone'         => $data['phone']
                ), true);

                //Get the activation code & prep data for email
                //$data['activationCode'] = $user->GetActivationCode();

                //send email with link to activate.
                /*Mail::send('emails.register_confirm', $data, function($m) use ($data) {
                 $m -> to($data['email']) -> subject('Thanks for Registration - Support Team');
                 });*/

                //If no groups exist then create new groups
                try {
                    $user_group = Sentry::findGroupById(1);
                } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
                    $this->createGroup('users');
                    $this->createGroup('admin');
                    $user_group = Sentry::findGroupById(1);
                }

                $user = User::find($user->getId()); //get user id from last login
                $user->addGroup($user_group); //add the user to default user group

                $userdata = [
                    'email' => $data['email'],
                    'password' => $data['password']
                ];

                $user = Sentry::authenticate($userdata, false); //login new user

                //success!
                Session::flash('success_msg', 'Thanks for signing up!'); //display success message
                return Redirect::to('/');

            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {

                Session::flash('error_msg', 'Username/Email Required.');
                return Redirect::back()
                    ->withErrors($data)
                    ->withInput(Input::except(array('password', 'password_confirmation')));

            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {

                Session::flash('error_msg', 'User Already Exist.');
                return Redirect::back()
                    ->withErrors($data)
                    ->withInput(Input::except(array('password', 'password_confirmation')));

            }

        }

        //$data['password'] = Hash::make($data['password']);

        //$this->user->create($data);

        //return Redirect::route('login.index');
    }


    /**
     * Show the form for editing the specified user.
     *
     * 
     * @return Response
     */
    public function edit()
    {
        $user = User::find(Sentry::getUser()->id); //get user object/details based off their session

        return View::make('account.index', compact('user'));
    }


    /**
     * Update the specified user in DB.
     *
     * 
     * @return Response
     */
    public function update()
    {
        $data = Input::all();

        $rules = [
            'first_name'            => 'required|min:2|max:25',
            'last_name'             => 'required|min:2|max:25',
            'phone'                 => 'required|max:14|min:8',
        ];

        $validation = Validator::make($data, $rules); //validate input against above criteria

        if ($validation->passes())
        {
            $user = User::find(Sentry::getUser()->id); //get user object based off their session

            $user->first_name    = $data['first_name'];
            $user->last_name     = $data['last_name'];
            $user->phone         = $data['phone'];

            $user->save(); //save updated details in DB

            return Redirect::back()
                ->withInput()
                ->withMessage('Account details were successfully edited!'); //return with success messages
        }

        return Redirect::back()
            ->withInput()
            ->withErrors($validation->errors()); //return with error message
    }
    /**
     * Store upgraded user details in DB.
     *
     * 
     * @return Response
     */
    public function storeUpgrade()
    {

        $data = Input::all();

        $rules = [
            'public_email'  => 'required|email|unique:users',
            'public_phone'  => 'required|max:14|min:8',
            'street'        => 'required|min:2',
            'town'          => 'required|min:3|max:25',
            'county'        => 'required|min:3',
            'postcode'      => 'required|min:6|max:9',
        ];

        $validation = Validator::make($data, $rules); //validate against above criteria

        if ($validation->passes())
        {
            $user = User::find(Sentry::getUser()->id); //get user object based off their session

            $user->public_phone = $data['public_phone'];
            $user->public_email = $data['public_email'];
            $user->street       = $data['street'];
            $user->town         = $data['town'];
            $user->county       = $data['county'];
            $user->postcode     = $data['postcode'];

            $user->save(); //save new details in DB against the user

            $manager_group = Sentry::findGroupByName('Property Managers');

            $user->addGroup($manager_group);

            return Redirect::back()
                ->withInput()
                ->withMessage('Your account has been upgraded to a property manager!');
        }

        return Redirect::back()
            ->withInput()
            ->withErrors($validation->errors());

    }

    /**
     * Creating group if doesn't exist
     *
     * @param Group name
     * @return Response
     */
    public function createGroup($groupName) {
        $input = array('newGroup' => $groupName);

        // Set Validation Rules
        $rules = array('newGroup' => 'required|min:4');

        //Run input validation
        $v = Validator::make($input, $rules);

        if ($v -> fails()) {
            return false;
        } else {
            try {
                $group = Sentry::getGroupProvider() -> create(array('name' => $input['newGroup'], 'permissions' => array('admin' => Input::get('adminPermissions', 0), 'users' => Input::get('userPermissions', 0), ), ));

                if ($group) {
                    return true;
                } else {
                    return false;
                }

            } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
                return false;
            } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
                return false;
            }
        }
    }
    /**
     * Store new user password in DB.
     *
     * 
     * @return Response
     */
    public function newPassword() {
        $data = Input::all();

        $rules = [
            'password'              => 'required|min:6|max:20|confirmed|regex:^(?=.*[A-Z])(?=.*[0-9]).{8}^',
            'password_confirmation' => 'required|min:6|max:20',
        ];

        $validation = Validator::make($data, $rules, User::$messages); //validate password against above criteria

        if ($validation->passes())
        {
            $user = User::find(Sentry::getUser()->id); //get user object based of their session

            $user->password = $data['password'];

            $user->save(); //save new password in DB

            return Redirect::back()
                ->withMessage('Password successfully reset!'); //return with sucess message
        }

        return Redirect::back()
            ->withErrors($validation->errors()); //return with errors
    }

}
