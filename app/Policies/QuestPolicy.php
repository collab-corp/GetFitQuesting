<?php

namespace App\Policies;

use App\User;
use App\Quest;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestPolicy
{
    use HandlesAuthorization, BypassedByAdmins;

    /**
     * Determine whether the user can view the quest.
     *
     * @param  \App\User  $user
     * @param  \App\Quest  $quest
     * @return mixed
     */
    public function view(User $user, Quest $quest)
    {
        return true;
    }

    /**
     * Determine whether the user can create quests.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the quest.
     *
     * @param  \App\User  $user
     * @param  \App\Quest  $quest
     * @return mixed
     */
    public function update(User $user, Quest $quest)
    {
        return $quest->creator->is($user);
    }

    /**
     * Determine whether the user can delete the quest.
     *
     * @param  \App\User  $user
     * @param  \App\Quest  $quest
     * @return mixed
     */
    public function delete(User $user, Quest $quest)
    {
        return $quest->creator->is($user);
    }
}
