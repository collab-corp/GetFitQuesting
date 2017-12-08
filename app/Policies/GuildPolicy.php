<?php

namespace App\Policies;

use App\User;
use App\Guild;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuildPolicy
{
    use HandlesAuthorization, BypassedByAdmins;

    /**
     * Determine whether the user can view the guild.
     *
     * @param  \App\User  $user
     * @param  \App\Guild  $guild
     * @return mixed
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine whether the user can create guilds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->exists;
    }

    /**
     * Determine whether the user can update the guild.
     *
     * @param  \App\User  $user
     * @param  \App\Guild  $guild
     * @return mixed
     */
    public function update(User $user, Guild $guild)
    {
        return $guild->creator->is($user);
    }

    /**
     * Determine whether the user can delete the guild.
     *
     * @param  \App\User  $user
     * @param  \App\Guild  $guild
     * @return mixed
     */
    public function delete(User $user, Guild $guild)
    {
        return $guild->creator->is($user);
    }
}
