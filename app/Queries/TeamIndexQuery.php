<?php

namespace App\Queries;

use App\Filters\TeamFilters;
use App\Team;
use Illuminate\Http\Request;

class TeamIndexQuery
{
    public function __construct(TeamFilters $filters, Request $request)
    {
        $this->filters = $filters;
        $this->request = $request;
    }

    public function __invoke()
    {
        $builder = Team::search(
            $this->request->get('search')
        );

        return $this->filters->apply($builder);
    }
}
