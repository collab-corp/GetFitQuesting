<?php

namespace App;

use App\Filters\Filters;
use Illuminate\Support\Arr;

trait Filterable
{
    /**
     * Eloquent scope for applying a filter.
     *
     * @param  \Illuminate\Database\Query\Builder 			$query
     * @param  \App\Filters\Filters|\Closure|null|array 	$filters
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeFilter($query, $filters = null)
    {
        foreach (Arr::wrap($filters) as $filter) {
            $this->applyFilterTo($query, $filter);
        }

        return $query;
    }

    /**
     * Apply a given filter to the Eloquent Query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder 		$query
     * @param  \App\Filters\Filters|\Closure|null|array 	$filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilterTo($query, $filter)
    {
        if ($filter instanceof Filters) {
            return $filter->apply($query);
        }

        if ($filter instanceof \Closure) {
            return $filter($query);
        }

        return $query;
    }
}
