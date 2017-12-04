<?php

namespace App\Queries;

use App\Filters\StoryFilters;
use App\Story;
use Illuminate\Http\Request;

class StoryIndexQuery
{
    public function __construct(StoryFilters $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke()
    {
        $request = $this->filters->getRequest();

        return $request->has('search')
            ? Story::search($request->get('search'))
            : Story::filter($this->filters);
    }
}
