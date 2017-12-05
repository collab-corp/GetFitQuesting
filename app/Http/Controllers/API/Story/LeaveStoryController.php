<?php

namespace App\Http\Controllers\API\Story;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\LeaveStoryRequest;
use App\Story;
use App\Team;
use Illuminate\Http\Request;

class LeaveStoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(Story $story, LeaveStoryRequest $request)
    {
        if ($request->has('team_id')) {
            Team::find($request->get('team_id'))->leave($story);
        } else {
            $request->user()->leave($story);
        }
    }
}
