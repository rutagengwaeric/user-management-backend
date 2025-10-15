<?php

namespace App\Policies;

use App\Models\Citizen;
use App\Models\User;

class CitizenPolicy
{
    public function viewAny(User $user)
    {
        return $user->isSystemAdmin() || $user->isLocalLeader() || $user->isPolicyMaker();
    }

    public function view(User $user, Citizen $citizen)
    {
        if ($user->isCitizen()) {
            return $citizen->user_id === $user->id;
        }
        
        return $user->isSystemAdmin() || $user->isLocalLeader() || $user->isPolicyMaker();
    }

    public function create(User $user)
    {
        return $user->isCitizen() || $user->isLocalLeader();
    }

    public function update(User $user, Citizen $citizen)
    {
        if ($user->isCitizen()) {
            return $citizen->user_id === $user->id;
        }
        
        return $user->isSystemAdmin() || $user->isLocalLeader();
    }

    public function delete(User $user, Citizen $citizen)
    {
        return $user->isSystemAdmin();
    }

    public function verify(User $user)
    {
        return $user->isSystemAdmin() || $user->isLocalLeader();
    }
}