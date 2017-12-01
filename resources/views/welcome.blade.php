@extends('layouts.app')

@section('content')
   '@inject('testimonials', 'App\Queries\LatestTestimonialsQuery')
    
    <h1 class="capitalize text-center">Get fit by questing!</h1>
    <article class="flex flex-col h-full container mx-auto intro">
        <h2>Something about how it works.</h2>
        <p><em>Why you'll love it</em></p>
    </article>

    <h1 class="capitalize text-center">What people are saying</h1>
    <article class="h-full container mx-auto overflow-hidden">
        <div class="slider">
            @forelse($testimonials(5) as $testimonial)
                <testimonial-card :testimonial="{{ json_encode($testimonial) }}"></testimonial-card>

            @empty
                <alert-solid>
                    Nobody has rated us, yet.
                </alert-solid>
            @endforelse
        </div>
    </article>
@endsection