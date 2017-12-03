<?php

namespace App\Filters;

use Illuminate\Http\Request;

/**
 * Request Filters.
 *
 * @credits Jeffrey Way
 * @link https://github.com/laracasts/Lets-Build-a-Forum-in-Laravel/blob/master/app/Filters/Filters.php
 */
abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * The Eloquent query.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->registerTraits();
    }

    /**
     * Register traits.
     * 
     * @return void
     */
    protected function registerTraits()
    {
        foreach (class_uses_recursive($this) as $trait) {
            $method = "register{$trait}";

            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    /**
     * Apply the filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        $this->query = $query;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->query;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
