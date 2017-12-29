<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
//use Socialite;
use App\Users;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /* public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }*/

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        if (Input::get('error') == 'access_denied') {

            return redirect('/');

        }  else {

            $user = Socialite::driver('google')->user();

            $data = [
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ];

            $id = Users::getId($data['email']);

            if ($id) {
                Auth::loginUsingId($id);

                return redirect('/');
                //return redirect()->route('/home');

            } else {
    //   return view('auth.login', ['loginStatus' => 'You account do not register']);
                return redirect('/login/?loginStatus=YOUR GOOGLE EMAIL IS NOT REGISTERED IN THE SYSTEM');

            }
        }
    }

    public function log()
    {

        Cache::flush();
        Auth::logout();
        Session::flush();

        return redirect('/');
    }



}
