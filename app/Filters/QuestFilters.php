<?php

namespace App\Filters;

class QuestFilters extends Filters
{
    protected $filters = ['difficulty'];

    protected function difficulty($value)
    {
    	$this->query->where('difficulty', $value);	
    }
}
