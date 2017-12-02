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
     * The quests the achiever has completed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quests()
    {
        return $this->belongsToMany(Quest::class, 'progress', null, null, null, null, Progress::class);
    }
}
