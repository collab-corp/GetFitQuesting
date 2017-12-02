<?php

namespace App\Policies;

use App\Admin;

trait BypassedByAdmins
{
    public function before($user, $ability)
    {
        if (Admin::check($user)) {
            return true;
        }
    }
}
