<?php

namespace App\Filters;

class StoryFilters extends Filters
{
    protected $filters = ['relations'];

    protected function relations($value)
    {
        $this->query->load(array_wrap($value));
    }
}
