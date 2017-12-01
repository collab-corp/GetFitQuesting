<?php

namespace App\Queries;

use App\Testimonial;

class LatestTestimonialsQuery
{
    public function __invoke(int $take = 5)
    {
        return Testimonial::with('author')->latest()->take($take)->get();
    }
}
