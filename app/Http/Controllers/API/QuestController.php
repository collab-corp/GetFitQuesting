<?php

namespace App\Http\Controllers\API;

use App\Filters\QuestFilters;
use App\Http\Controllers\Controller;
use App\Queries\QuestsIndexQuery;
use App\Quest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->authorizeResource(Quest::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuestFilters $filters)
    {
        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:25',
            'columns' => 'nullable|array',
            'pageName' => 'nullable|string',
            'page' => 'nullable|integer',

            'type' => ['string', Rule::in(Quest::validTypes())],
            'search' => 'string|min:2|max:40'
        ]);

        $builder = request()->has('search')
            ? Quest::search(request('search'))
            : Quest::query();

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
        return Quest::create(
            $request->validate([
                'difficulty' => 'required|integer|in:1,2,3,4,5',
                'experience' => 'required|integer',
                'name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string|slug',
                'type' => ['required', 'string', Rule::in(Quest::validTypes())],
                'description' => 'required|string|max:255'
            ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Quest::with(['tags', 'media'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quest  $quest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quest $quest)
    {
        return tap($quest)->update($request->validate([
            'difficulty' => 'integer|in:1,2,3,4,5',
            'experience' => 'integer',
            'name' => 'string|max:255|min:3',
            'slug' => 'nullable|string|slug',
            'type' => ['string', Rule::in(Quest::validTypes())],
            'description' => 'string|max:255'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quest $quest)
    {
        $quest->delete();
    }
}
