<?php

namespace App;

use App\Achievements\{Completed5Quests, Completed10Quests, CompletedFirstStory};
use Gstt\Achievements\Model\{AchievementDetails, AchievementProgress};

class Achievement 
{
	/**
	 * Get the general achievements.
	 * 
	 * @param  string $type [quest,story,...]
	 * @return \Illuminate\Support\Collection
	 */
	public static function general(string $type)
	{
		return collect([
			'quest' => [
				Completed5Quests::class,
				Completed10Quests::class,
			],
			'story' => [
				CompletedFirstStory::class
			]
		])->filter(function ($values, $key) use ($type) {
			return $type == $key;
		})->collapse();
	}

	/**
	 * Scope the achievements unlocked by progress.
	 * 
	 * @param  \App\Progress $progress
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function unlockedWith($progress)
	{
		$obtained = AchievementProgress::where('achiever_type', get_class($progress->user))
                                       ->where('achiever_id', $progress->user->id)
                                       ->when($progress->team, function ($query, $team) {
                                       		$query->orWhere(function ($query) use($team) {
                                       			$query->where('achiever_type', get_class($team))
                                       				  ->where('achiever_id', $team->id);
                                       		});
                                       })
                                       ->pluck('achievement_id');
        
		return AchievementDetails::whereNotIn('id', $obtained)
			->where('points', '<=', $progress->experience);
	}
}