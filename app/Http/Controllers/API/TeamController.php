<?php

namespace App\Http\Controllers\API;

use App\Filters\TeamFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Queries\TeamIndexQuery;
use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeamFilters $filters)
    {
        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:25',
            'columns' => 'nullable|array',
            'pageName' => 'nullable|string',
            'page' => 'nullable|integer',
            
            'sort_by' => 'string',
            'sort_by_desc' => 'string',
            'search' => 'string|min:2|max:40'
        ]);

        $builder = request()->has('search')
            ? Team::search(request('search'))
            : Team::query();

        return $filters->apply($builder)->paginate(
            request('perPage'),
            request('columns'),
            request('pageName'),
            request('page')
        );
    }

    public function store(StoreTeamRequest $request)
    {
        $this->authorize('create', new Team);

        return Team::create([
            'name' => $request->name,
            'guild_id' => $request->guild_id,
            'owner_id' => $request->user()->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return $team->load(['owner', 'achievements']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        return tap($team)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $team->delete();
    }
}
