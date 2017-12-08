<?php

namespace App\Http\Controllers\API\Guild;

use App\Guild;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveGuildController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api');
    }

    public function __invoke(Guild $guild)
    {
    	$guildMember = $guild->members()->whereHas('user', function ($query) {
    		$query->whereKey(request()->user()->getKey());
    	});

    	abort_unless($guildMember->exists(), 422, "Cannot leave a guild you're not a member of.");

    	$guildMember->delete();
    }
}
