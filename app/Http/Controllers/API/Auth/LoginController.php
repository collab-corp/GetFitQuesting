<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function __invoke(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        return $this->guard->attempt($request->only(['email', 'password']))
            ? $this->sendSuccessResponse($request)
            : $this->sendFailureResponse($request);
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'email';
    }

    protected function sendSuccessResponse($request)
    {
        $this->clearLoginAttempts($request);

        $request->user()->withAccessToken(
        	$request->user()->createToken('auth')->accessToken
        )->append('access_token');

        return response()->json($request->user());
    }
    
    protected function sendFailureResponse($request)
    {
        $this->incrementLoginAttempts($request);

        return response()->json(['password' => trans('auth.failed')], 422);
    }
}
