<?php

namespace App\Http\Controllers\API;

use App\Filters\NewsFilters;
use App\Http\Controllers\Controller;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(News::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NewsFilters $filters)
    {
        return News::with('author')->filter($filters)->get();
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
            'title'     =>  ['required', 'string', 'max:191'],
            'content'   =>  ['required', 'string'],
            'images'    =>  ['array'],
            'images.*'  =>  ['file', 'image'],
            'videos'    =>  ['array'],
            'videos.*'  =>  ['file', 'mimetypes:video/avi,video/mpeg,video/quicktime']
        ]);

        return $request->user()->news()->create(
            $request->intersect(['title', 'content', 'images', 'videos'])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show($news_id)
    {
        return News::with(['media', 'author'])->findOrFail($news_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $this->validate($request, [
            'author_id' =>  ['integer', Rule::exists('users', 'id')],
            'title'     =>  ['string', 'max:191'],
            'content'   =>  ['string'],
            'images'    =>  ['array'],
            'images.*'  =>  ['file', 'image'],
            'videos'    =>  ['array'],
            'videos.*'  =>  ['file', 'mimetypes:video/avi,video/mpeg,video/quicktime']
        ]);

        return $news->update(
            $request->intersect(['author_id', 'title', 'content', 'images', 'videos'])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
    }
}
