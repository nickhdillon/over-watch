<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Release;
use App\Models\User;

class ReleasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Release $release): bool
    {
        return $release->project->isAccessibleBy($user);
    }

    public function create(User $user, Project $project): bool
    {
        return $project->isAccessibleBy($user);
    }

    public function update(User $user, Release $release): bool
    {
        return $this->view($user, $release);
    }

    public function delete(User $user, Release $release): bool
    {
        return $this->view($user, $release);
    }

    public function restore(User $user, Release $release): bool
    {
        return $this->view($user, $release);
    }

    public function forceDelete(User $user, Release $release): bool
    {
        return $this->view($user, $release);
    }
}
