<?php

namespace App;

use App\Filterable;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;
use Spatie\Tags\HasTags;

class News extends Model implements HasMediaConversions
{
    use SoftDeletes, Searchable, HasMediaTrait, Filterable, HasTags;

    protected $fillable = [
        'published',
        'title','content',
        'slug',
        'author_id'
    ];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $appends = ['images'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($news) {
            if (! $news->link) {
                $news->update(['link' => url('news', $news)]);
            }
        });

        static::saving(function ($news) {
            if (! $news->slug) {
                $news->slug = str_slug($news->name);
            }
        });

        static::addGlobalScope('onlyPublished', function ($query) {
            $query->wherePublished(true);
        });

        static::addGlobalScope('withTags', function ($query) {
            $query->with('tags');
        });
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('images')->map(function (Media $media) {
            return [
                'path' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb')
            ];
        });
    }

    public function scopeOnlyDrafts($query)
    {
        return $this->scopeOnlyUnpublished($query);
    }

    public function scopeWithDrafts($query)
    {
        return $this->scopeWithUnpublished($query);
    }

    public function scopeOnlyUnpublished($query)
    {
        $query->withoutGlobalScope('onlyPublished')->wherePublished(false);
    }

    public function scopeWithUnpublished($query)
    {
        $query->withoutGlobalScope('onlyPublished');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }

    public function publish($date = null)
    {
        return tap($this)->update([
            'published'     =>  true,
            'published_at'  =>  $date ?? Carbon::now()
        ]);
    }

    public function addMediaFiles($files, string $collection = 'default')
    {
        foreach ($files as $file) {
            if (is_string($file) && Str::startsWith($file, ['http', 'https'])) {
                $this->addMediaFromUrl($file)
                     ->toMediaCollection($collection);
            } else {
                $this->addMedia($file)
                     ->toMediaCollection($collection);
            }
        }
    }

    public function searchableAs()
    {
        return 'news';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
    }
}
