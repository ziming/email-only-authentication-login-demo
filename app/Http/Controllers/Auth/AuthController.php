<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\LoginToken;
use App\AuthenticatesUser;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    /**
     * @var AuthenticatesUser
     */
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @param AuthenticatesUser $auth
     */
    public function __construct(AuthenticatesUser $auth)
    {
        $this->auth = $auth;
    }

    public function login()
    {
        return view('login');
    }

    public function postLogin()
    {
        // can wrap in try catch if you want
        $this->$auth->invite();

        return 'Check your email for the link to login!';
    }

    /**
     * Login the user, using the given token.
     *
     * @param  LoginToken $token
     * @return string
     */
    public function authenticate(LoginToken $token)
    {
        $this->auth->login($token);
        return redirect('dashboard');
    }

    /**
     * Log out the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Or put this on AuthenticatesUser, and
        // do $this->auth->logout();
        auth()->logout();
        return redirect('/');
    }
}
