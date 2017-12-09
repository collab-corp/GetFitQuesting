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

    protected $fillable = ['name', 'owner_id', 'guild_id'];

	protected static function boot()
	{
		parent::boot();

		static::deleted(function ($team) {
			$team->users->each->notify(new TeamDisbanded($team));
		});
	}

	public function guild() 
	{
		return $this->belongsTo(Guild::class);
	}

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
	public function toSearchableArray()
	{
		return [
            'name' => $this->name,
            'guild' => optional($this->guild)->name
        ];
	}
}
