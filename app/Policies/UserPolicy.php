<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->isSystemAdmin();
    }

    public function update(User $user, User $model)
    {
        return $user->isSystemAdmin();
    }

    public function delete(User $user, User $model)
    {
        return $user->isSystemAdmin() && $user->id !== $model->id;
    }
}