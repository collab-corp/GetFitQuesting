<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Story extends Model
{
    use Rememberable;

    protected $fillable = [
        'name', 'body', 'creator_id'
    ];

    /**
     * Get the stories enrolled by given User or Team.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $achiever
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnrolledBy($query, $achiever)
    {
        if ($achiever instanceof User) {
            $query->whereHas('users', function ($query) use ($achiever) {
                $query->where('user_id', $achiever->id);
            });
        } else {
            $query->whereHas('teams', function ($query) use ($achiever) {
                $query->where('team_id', $achiever->id);
            });
        }

        return $query;
    }

    /**
     * The users enrolled in this story.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'progress', null, null, null, null, Progress::class);
    }

    /**
     * The teams enrolled in this story.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'progress', null, null, null, null, Progress::class);
    }

    /**
     * The creator of this story.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The quests that make up this story.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quests()
    {
        return $this->belongsToMany(Quest::class, 'quest_story')
            ->withTimestamps()
            ->withPivot('position');
    }
}
