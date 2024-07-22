<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Permissions\V1\Abilities;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function delete(User $currentUser, User $user): int
    {
        return $currentUser->tokenCan(Abilities::DeleteUser);
    }

    public function store(User $currentUser): int
    {
        return $currentUser->tokenCan(Abilities::CreateUser);
    }

    public function replace(User $currentUser, User $user): int
    {
        return $currentUser->tokenCan(Abilities::ReplaceUser);
    }

    public function update(User $currentUser, User $user): int
    {
        return $currentUser->tokenCan(Abilities::UpdateUser);
    }
}
