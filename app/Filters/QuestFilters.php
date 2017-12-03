<?php

namespace App\Filters;

class QuestFilters extends Filters
{
    protected $filters = ['type'];

    protected function type($value)
    {
        return $this->query->where('type', $value);
    }
}
