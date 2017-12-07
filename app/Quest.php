<?php

namespace App;

use App\Filterable;
use Gstt\Achievements\Model\AchievementDetails;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\Tags\HasTags;
use Watson\Rememberable\Rememberable;

class Quest extends Model implements HasMedia
{
    use Rememberable, Searchable, HasMediaTrait, Filterable, HasTags;
    
    /**
     * The fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'difficulty', 'experience',
        'link', 'name', 'slug', 'type',
        'description', 'creator_id'
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($quest) {
            if (! $quest->link) {
                $quest->update(['link' => url('quest', $quest)]);
            }

            $quest->achievements()->saveMany(
                \App\Achievement::general('quest')->map(function ($achievement) {
                    return (new $achievement)->getModel();
                })
            );
        });

        static::saving(function ($quest) {
            if (! $quest->slug) {
                $quest->slug = str_slug($quest->name);
            }
        });
    }

    /**
     * Get the valid quest types.
     *
     * @return array
     */
    public static function validTypes()
    {
        return ['cardio', 'strength', 'power', 'muscle', 'event'];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the amount of points the quest yields.
     *
     * @return integer
     */
    public function getPointsAttribute()
    {
        return ($this->experience * $this->difficulty);
    }

    /**
     * Complete the quest, adding to achievement & progress.
     *
     * @param  \App\User | \App\Team $achiever
     * @return \App\Progress
     */
    public function complete($achiever)
    {
        $this->achievements()
            ->whereNotIn('achievement_id', $achiever->achievements->pluck('id'))
            ->select(['class_name'])
            ->each(function ($details) use ($achiever) {
                $achiever->addProgress($details->getClass(), $this->points);
            });

        return $achiever->progress()->create([
            'quest_id' => $this->id,
            'experience' => $this->points
        ]);
    }

    /**
     * Add media files to the quest.
     *
     * @param mixed $files
     * @param string $collection
     */
    public function addMediaFiles($files, string $collection = 'default')
    {
        foreach (array_wrap($files) as $file) {
            if (is_url($file)) {
                $this->addMediaFromUrl($file)
                     ->toMediaCollection($collection);
            } else {
                $this->addMedia($file)
                     ->toMediaCollection($collection);
            }
        }
    }

    /**
     * The stories this quest is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stories()
    {
        return $this->belongsToMany(Story::class, 'quest_story');
    }

    /**
     * The achievements that can be obtained by completing this quest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function achievements()
    {
        return $this->belongsToMany(AchievementDetails::class, 'achievement_quest', 'quest_id', 'achievement_id');
    }

    /**
     * The quest creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The fields to be searchable.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'difficulty' => $this->difficulty
        ];
    }
}
