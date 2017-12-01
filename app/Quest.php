<?php

namespace App;

use App\Filterable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Tags\HasTags;
use Watson\Rememberable\Rememberable;

class Quest extends Model
{
    use Rememberable, Searchable, HasMediaTrait, Filterable, HasTags;
    
    protected $fillable = [
        'difficulty', 'experience',
        'requires_team',
        'link', 'name', 'slug', 'type',
        'description'
    ];

    protected $casts = [
        'requires_team' => 'boolean'
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($quest) {
            if (! $quest->link) {
                $quest->update(['link' => url('quest', $quest)]);
            }
        });

        static::saving(function ($quest) {
            if (! $quest->slug) {
                $quest->slug = str_slug($quest->name);
            }
        });

        static::addGlobalScope('withTags', function ($query) {
            $query->with('tags');
        });
    }

    public function complete($user, $team = null)
    {
        return $user->progress()->create([
            'user_id' => $user->id,
            'quest_id' => $this->id,
            'team_id' => optional($team)->id
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
        return 'quests';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
    }
}
