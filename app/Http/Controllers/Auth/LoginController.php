<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    static protected $valid_providers = [
        'google',
    ];

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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {
        $provider = $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $provider = $this->validateProvider($provider);
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/');
        }

        $existing_user = User::where('email', $user->email)->first();

        if ($existing_user) {
            auth()->login($existing_user, true);
        } else {
            $new_user = new User();
            $new_user->name = $user->name;
            $new_user->email = $user->email;
            $new_user->email_verified_at = now();
            $new_user->password = Hash::make(md5(now()));
            $new_user->avatar = $user->avatar;
            $new_user->save();
            auth()->login($new_user, true);
        }

        return redirect('/');
    }

    private function validateProvider($provider) {
        $provider = strtolower($provider);
        if (!in_array($provider, static::$valid_providers, true)) {
            throw new NotFoundHttpException();
        }

        return $provider;
    }
}
