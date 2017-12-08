<?php

namespace App\Filters;

trait SortFilters
{
	public function registerSortFilters()
	{
		$filters = array_merge($this->filters, ['sort_by', 'sort_by_desc']);

		$this->filters = array_unique($filters);
	}

    protected function sortBy($value)
    {
        $this->query->orderBy($value, 'asc');
    }

    protected function sortByDesc($value)
    {
        $this->query->orderBy($value, 'desc');
    }
}