<?php

namespace App\Queries;

use App\Models\News;

class LatestNewsQuery
{
    public function __invoke(int $take = 5)
    {
        return News::latest()->take($take)->get();
    }
}
