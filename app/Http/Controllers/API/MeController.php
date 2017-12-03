<?php

namespace App\Http\Controllers\API;

use App\Filters\MeFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\MeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(MeRequest $request)
    {
        return tap(request()->user(), function ($user) {
            if (request()->has('relations')) {
                $user->load(request('relations'));
            }
        });
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
            'avatar' => 'image'
        ]);

        return tap($request->user(), function ($user) use($request) {
            $user->name = data_get($request, 'name', $user->name);
            $user->email = data_get($request, 'email', $user->email);

            if ($request->has('password')) {
                $user->password = Hash::make($request->get('password'));
            }

            if ($request->has('avatar')) {
                $user->avatar = $request->file('avatar')->store('avatars', 's3');
            }

            $user->saveOrFail();
        });
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();
    }
}
