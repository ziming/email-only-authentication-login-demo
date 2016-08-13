<?php 

namespace App;

use App\LoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Passwords\createToken;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpKernel\EventListener\validateRequest;

class AuthenticatesUser
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    
    use ValidatesRequests;

    /**
     * Send a sign in invitation to the user.
     */
    public function invite()
    {
        $this->validateRequest()
             ->createToken()
             ->send();
    }

    /**
     * Validate the request data.
     *
     * @return $this
     */
    protected function validateRequest()
    {
        $this->validate($this->request, [
            'email' => 'required|email|exists:users'
        ]);
        return $this;
    }

    /**
     * Prepare a log in token for the user.
     *
     * @return LoginToken
     */
    protected function createToken()
    {
        $user = User::byEmail($this->request->email);
        return LoginToken::generateFor($user);
    }

    /**
     * Log in the user associated with a token.
     *
     * @param  LoginToken $token
     * @return void
     */
    public function login(LoginToken $token)
    {
        Auth::login($token->user);
        $token->delete();
    }
}
