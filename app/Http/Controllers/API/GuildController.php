<?php

namespace App\Http\Controllers\API;

use App\Filters\GuildFilters;
use App\Guild;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuildController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->authorizeResource(Guild::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GuildFilters $filters)
    {
        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:25',
            'columns' => 'nullable|array',
            'pageName' => 'nullable|string',
            'page' => 'nullable|integer',

            'search' => 'string|min:2|max:40'
        ]);

        $builder = request()->has('search')
            ? Guild::search(request('search'))
            : Guild::query();

        return $filters->apply($builder)->paginate(
            request('perPage'),
            request('columns'),
            request('pageName'),
            request('page')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:4|max:45|unique:guilds,name',
            'description' => 'nullable|string|max:255',
            'banner' => ['image', Rule::dimensions()->minWidth(1280)->minHeight(1024)]
        ]);

        return $request->user()->guilds()->create([
            'name' => $request['name'],
            'description' => $request['description'],
            'banner' => optional($request['banner'])->store('guilds', 's3'),
            'creator_id' => $request->user()->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guild  $guild
     * @return \Illuminate\Http\Response
     */
    public function show(Guild $guild)
    {
        return $guild->load(['media', 'creator']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Guild  $guild
     * @return \Illuminate\Http\Response
     */
    public function update(Guild $guild)
    {
        $this->validate(request(), [
            'name' => 'string|min:4|max:45|unique:guilds,name',
            'description' => 'nullable|string|max:255',
            'banner' => ['image', Rule::dimensions()->minWidth(1280)->minHeight(1024)]
        ]);

        return tap($guild, function ($guild) {
            $values = request(['name', 'description', 'banner']);

            if (array_key_exists('banner', $values)) {
                $values['banner'] = $values['banner']->store('guilds', 's3');
            }

            $guild->update($values);
        });

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guild  $guild
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guild $guild)
    {
        $guild->delete();
    }
}
