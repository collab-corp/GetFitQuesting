<?php

namespace App;

use App\Events\Progress\ProgressCreated;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';

    protected $fillable = ['quest_id', 'story_id', 'user_id', 'team_id', 'experience'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($progress) {
            optional($progress->user)->computeExperience();
            optional($progress->team)->computeExperience();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}
