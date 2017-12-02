<?php

namespace App\Filters;

use App\Models\News;

class QuestFilters extends Filters
{
    protected $filters = ['type'];

    protected function type($value)
    {
        return $this->query->where('type', $value);
    }
}
