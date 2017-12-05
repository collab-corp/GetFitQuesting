<?php

namespace App;

trait HasProgress
{
    /**
     * Compute the experience.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function computeExperience()
    {
        return tap($this)->update(['experience' => $this->progress->sum('experience')]);
    }

    /**
     * The progress relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * Enroll a story.
     *
     * @param  \App\Story $story
     * @return \App\Progress
     */
    public function enroll($story)
    {
        return $this->progress()->create(['story_id' => $story->id]);
    }

    /**
     * Leave a given story.
     *
     * @param  \App\Story $story
     * @return void
     */
    public function leave($story)
    {
        $this->progress()
            ->where('story_id', $story->id)
            ->delete();
    }

    /**
     * The stories the achiever is enrolled in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function stories()
    {
        return $this->belongsToMany(Story::class, 'progress', null, null, null, null, Progress::class);
    }

    /**
     * The quests the achiever has completed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quests()
    {
        return $this->belongsToMany(Quest::class, 'progress', null, null, null, null, Progress::class);
    }
}
