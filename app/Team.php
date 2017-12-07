<?php

namespace App;

use App\Notifications\Team\TeamDisbanded;
use Gstt\Achievements\Achiever;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Teamwork\TeamworkTeam;
use Watson\Rememberable\Rememberable;

class Team extends TeamworkTeam
{
	use Achiever, Rememberable, HasProgress, SoftDeletes;

	protected static function boot()
	{
		parent::boot();

		static::deleted(function ($team) {
			$team->users->each->notify(new TeamDisbanded($team));
		});
	}

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
	public function toSearchableArray()
	{
		return [
            'name' => $this->name
        ];
	}
}
