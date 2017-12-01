<?php

namespace Tests\Feature;

use App\News;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\InMemoryDatabase;
use Tests\TestCase;

class FilteringNewsTest extends TestCase
{
    use RefreshDatabase, InMemoryDatabase;

    protected function setUp()
    {
        parent::setUp();

        News::disableSearchSyncing();
    }

    /**
     * Spatie/laravel-tags
     * depends MySQL Connection (Translatable query expressions)
     * depends Real Events
     *
     * @covers App\Http\Controllers\API\NewsController@index
     * @covers App\Filters\NewsFilters@tags
     * @covers Spatie\Tags\HasTags@withAnyTags
     *
     * @test
     */
    public function canFilterByTags()
    {
        $this->enableExceptionHandling();

        $news = factory(News::class)->states('published')->create([
            'title' => 'Flashy new features',
            'content' => 'We now offer {insert fancy stuff}!'
        ])->attachTags(['features', 'game']);

        $this->json('GET', '/api/news?tags[]=game')
             ->assertSuccessful()
             ->assertJsonFragment([
                'title' => 'Flashy new features',
                'content' => 'We now offer {insert fancy stuff}!',
             ]);
    }

    /**
     * @covers App\Http\Controllers\API\NewsController@index
     * @covers App\Filters\NewsFilters@latest
     *
     * @test
     */
    public function canFilterByLatest()
    {
        \Event::fake();

        factory(News::class)->times(5)->create()
        ->random(3)
        ->each->update(['created_at' => Faker::create()->dateTimeThisMonth()]);

        $this->json('GET', '/api/news?latest')
           ->assertStatus(200)
           ->assertJson(News::latest()->get()->jsonSerialize());
    }

    /**
     * @covers App\Http\Controllers\API\NewsController@index
     * @covers App\Filters\NewsFilters@oldest
     *
     * @test
     */
    public function canFilterByOldest()
    {
        \Event::fake();

        factory(News::class)->times(5)->create()
        ->random(3)
        ->each->update(['created_at' => Faker::create()->dateTimeThisMonth()]);

        $this->json('GET', '/api/news?oldest')
           ->assertStatus(200)
           ->assertJson(News::oldest()->get()->jsonSerialize());
    }

    /**
     * @covers App\Http\Controllers\API\NewsController@index
     * @covers App\Filters\NewsFilters@published_at
     *
     * @test
     */
    public function canFilterByPublishingDate()
    {
        \Event::fake();

        factory(News::class)->states('published')->create([
            'title' => 'Flashy new features',
            'content' => 'We now offer {insert fancy stuff}!',
            'published_at' => '1970-01-01 02:01:10'
        ]);

        $this->json('GET', '/api/news?published_at=1970-01-01')
             ->assertStatus(200)
             ->assertJsonFragment([
                'title' => 'Flashy new features',
                'content' => 'We now offer {insert fancy stuff}!',
             ]);
    }

    /**
      * @covers App\Http\Controllers\API\NewsController@index
      * @covers App\Filters\NewsFilters@by
      *
      * @test
      */
    public function canFilterByAuthorName()
    {
        \Event::fake();

        factory(News::class)->states('published')->create([
          'title'       =>  'Flashy new features',
          'content'     =>  'We now offer {insert fancy stuff}!',
          'author_id'   =>  create(User::class, ['name' => 'John Doe'])->id
        ]);

        $this->json('GET', '/api/news?by=John+Doe')
         ->assertStatus(200)
         ->assertJsonFragment([
          'title'   =>  'Flashy new features',
          'content' =>  'We now offer {insert fancy stuff}!',
         ]);
    }

    /**
      * @covers App\Http\Controllers\API\NewsController@index
      * @covers App\Filters\NewsFilters@author_id
      *
      * @test
      */
    public function canFilterByAuthorId()
    {
        \Event::fake();

        factory(News::class)->states('published')->create([
            'title'  => 'Flashy new features',
            'content' => 'We now offer {insert fancy stuff}!',
            'author_id' => 1
        ]);

        $this->json('GET', '/api/news?author_id=1')
             ->assertStatus(200)
             ->assertJsonFragment([
                'title' => 'Flashy new features',
                'content' => 'We now offer {insert fancy stuff}!',
             ]);
    }

    /**
      * @covers App\Http\Controllers\API\NewsController@index
      * @covers App\Filters\NewsFilters@take
      *
      * @test
      */
    public function canLimitTheAmountOfResults()
    {
        \Event::fake();

        factory(News::class)->states('published')->times(11)->create();

        $this->json('GET', '/api/news?take=10')
         ->assertStatus(200)
         ->assertCount(11);
    }
}
