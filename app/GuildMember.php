<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    protected $fillable = [
    	'user_id', 'guild_id', 'description'
    ];

    protected static function boot()
    {
    	parent::boot();

    	static::created(function ($member) {
    		$member->guild->update([
    			'members_count' => $member->guild->members_count +1
    		]);
    	});

    	static::deleted(function ($member) {
    		$member->guild->update([
    			'members_count' => $member->guild->members_count -1
    		]);
    	});

    	static::updating(function ($member) {
    		if ($member->isDirty('guild_id') && $member->guild_id !== $member->getOriginal('guild_id')) {
    			$previousGuild = Guild::find($member->getOriginal('guild_id'));
    			$newGuild = Guild::find($member->guild_id);

    			$previousGuild->update([
    				'members_count' => $previousGuild->members_count -1
    			]);

    			$newGuild->update([
    				'members_count' => $newGuild->members_count +1
    			]);
    		}

    		$member->guild->update([
    			'members_count' => $member->guild->members_count -1
    		]);
    	});
    }

    public function user() 
    {
    	return $this->belongsTo(User::class);
    }

    public function guild() 
    {
    	return $this->belongsTo(Guild::class);
    }
}
