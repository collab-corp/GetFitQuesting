<?php

namespace App\Http\Controllers\API\Story;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\EnrollStoryRequest;
use App\Story;
use App\Team;
use Illuminate\Http\Request;

class EnrollStoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(Story $story, EnrollStoryRequest $request)
    {
        if ($request->has('team_id')) {
            return Team::find($request->get('team_id'))->enroll($story);
        }

        return $request->user()->enroll($story);
    }
}
