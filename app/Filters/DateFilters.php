<?php

namespace App\Filters;

trait DateFilters
{
	protected $date_column = 'created_at';

	public function registerDateFilters()
	{
		$filters = array_merge($this->filters, ['before', 'after', 'until', 'earliest', 'at', 'aproxy']);

		$this->filters = array_unique($filters);
	}

	protected function earliest($date)
	{
		return $this->aproxy($date);
	}

	protected function aproxy($date)
	{
		$this->builder->whereDate($this->date_column, '>=', $date);

		return $this;
	}

	protected function at($date)
	{
		$this->builder->whereDate($this->date_column, '=', $date);

		return $this;
	}

	protected function before($date)
	{
		$this->builder->whereDate($this->date_column, '<', $date);

		return $this;
	}

	protected function after($date)
	{
		$this->builder->whereDate($this->date_column, '>', $date);

		return $this;
	}

	protected function until($date)
	{
		$this->builder->whereDate($this->date_column, '<=', $date);

		return $this;
	}
}