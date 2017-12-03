<?php

namespace App;

use App\Jobs\User\ScrapOldAvatar;
use App\Models\AccountUserPivot;
use App\Models\Auth\Account;
use App\Models\News;
use Gstt\Achievements\Achiever;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Mpociot\Teamwork\Traits\UserHasTeams;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, UserHasTeams, Achiever, HasProgress, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($user) {
            if ($user->isDirty('avatar')) {
                ScrapOldAvatar::dispatch($user);
            }
        });
    }

    public function getAvatarAttribute(string $avatar = null)
    {
        if (empty($avatar)) {
            return 'https://www.gravatar.com/avatar/d5570db0d14ecdc8b629e6d03507d577.jpg?s=200&d=mm';
        }

        if (is_url($avatar)) {
            return $avatar;
        }

        return Storage::url($avatar);
    }

    public function onAnyTeam($teams)
    {
        foreach ($teams as $team) {
            if ($this->onTeam($team)) {
                return true;
            }
        }

        return false;
    }

    public function onTeam($team)
    {
        if (is_string($team)) {
            $team = Team::where('name', $team)->firstOrFail();
        }

        return $this->teams->contains($team);
    }

    public function ownsTeam($team)
    {
        if (is_string($team)) {
            $team = Team::where('name', $team)->firstOrFail();
        }

        return $this->id && $team->owner_id && $this->id == $team->owner_id;
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'author_id');
    }
}
