<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Http\Request;
use Laravel\Passport\Guards\TokenGuard;

class RegisterController extends Controller
{
    public function __construct(EventDispatcher $dispatcher, Guard $guard)
    {
        $this->events = $dispatcher;
        $this->guard = $guard;
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $this->events->fire(new Registered($user));
        $this->guard->login($user);

        $user->withAccessToken(
            $user->createToken('auth')->accessToken
        )->append('access_token');

        return response()->json($user);
    }
}
