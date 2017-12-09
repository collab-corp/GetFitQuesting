<?php

namespace App\Http\Controllers\API\Guild;

use App\Guild;
use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Http\Request;

class GuildTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Guild $guild)
    {
        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:25',
            'columns' => 'nullable|array',
            'pageName' => 'nullable|string',
            'page' => 'nullable|integer'
        ]);

        return $guild->teams()->paginate(
            request('perPage'),
            request('columns'),
            request('pageName'),
            request('page')
        );
    }
}
