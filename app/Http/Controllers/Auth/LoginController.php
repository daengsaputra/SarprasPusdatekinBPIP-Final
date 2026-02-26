<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/dashboard';

    /**
     * Use a single login field that accepts email or username.
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Build credentials for email-or-username login.
     */
    protected function credentials(Request $request)
    {
        $login = trim((string) $request->input('login'));
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if ($field === 'email') {
            $login = strtolower($login);
        }

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Limit login attempts to reduce brute-force attacks.
     */
    protected function maxAttempts()
    {
        return 5;
    }

    /**
     * Lockout window in minutes.
     */
    protected function decayMinutes()
    {
        return 1;
    }

    /**
     * Redirect users to login page after logout.
     */
    protected function loggedOut($request)
    {
        return redirect('/login');
    }
}
