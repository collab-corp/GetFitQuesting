<?php

namespace App\Http\Controllers\API;

use App\Filters\MeFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\MeRequest;
use Illuminate\Http\Request;

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
}
