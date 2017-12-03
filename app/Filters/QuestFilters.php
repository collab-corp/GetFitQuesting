<?php

namespace App\Filters;

class QuestFilters extends Filters
{
    protected $filters = ['type', 'difficulty'];

    protected function type($value)
    {
        $this->query->where('type', $value);
    }

    protected function difficulty($value)
    {
    	$this->query->where('difficulty', $value);	
    }
}
