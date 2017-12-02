<?php

namespace App\Queries;

use App\Filters\QuestFilters;
use App\Models\News;
use App\Quest;
use Illuminate\Http\Request;

class QuestsIndexQuery
{
    public function __construct(QuestFilters $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke($request = null)
    {
        $request = $request ?? request();

        return $request->has('search')
            ? Quest::search($request->get('search'))
            : Quest::filter($this->filters);
    }
}
