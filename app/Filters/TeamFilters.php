<?php

namespace App\Filters;

class TeamFilters extends Filters
{
    protected $filters = ['sort_by', 'sort_by_desc'];

    protected function sortBy($value)
    {
        $this->query->orderBy($value, 'asc');
    }

    protected function sortByDesc($value)
    {
        $this->query->orderBy($value, 'desc');
    }
}
