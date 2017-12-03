<?php
namespace App\Filters;

use App\Models\News;

class NewsFilters extends Filters
{
    use DateFilters;

    protected $filters = ['tags', 'published_at', 'author_id', 'by'];

    protected function tags($value)
    {
        return $this->query->withAnyTags($value);
    }

    protected function published_at($value)
    {
        return $this->query->whereDate('published_at', $value);
    }

    protected function by($value)
    {
        return $this->query->whereHas('author', function ($query) use ($value) {
            return $query->where('name', $value);
        });
    }

    protected function author_id($value)
    {
        return $this->query->where('author_id', $value);
    }

    protected function take($value)
    {
        return $this->query->take($value);
    }
}
