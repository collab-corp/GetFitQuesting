<?php

namespace App\Queries;

use App\Filters\QuestFilters;
use App\Quest;
use Illuminate\Http\Request;

class QuestsIndexQuery
{
    public function __construct(QuestFilters $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke()
    {
        $request = $this->filters->getRequest();

        return $request->has('search')
            ? Quest::search($request->get('search'))
            : Quest::filter($this->filters);
    }
}
