<?php
namespace App\Filters;

use App\Models\News;

class NewsFilters extends Filters
{
    protected $filters = ['tags', 'latest', 'oldest', 'published_at', 'author_id', 'by'];

    protected function tags($value)
    {
        return $this->query->withAnyTags($value);
    }

    protected function latest($value = null)
    {
        return $this->query->latest($value ?? 'created_at');
    }

    protected function oldest($value = null)
    {
        return $this->query->oldest($value ?? 'created_at');
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
