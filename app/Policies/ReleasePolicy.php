<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReleasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Release $release): Response
    {
        return $this->accessResponse($release->project->isAccessibleBy($user));
    }

    public function create(User $user, Project $project): Response
    {
        return $this->accessResponse($project->isAccessibleBy($user));
    }

    public function update(User $user, Release $release): Response
    {
        return $this->view($user, $release);
    }

    public function delete(User $user, Release $release): Response
    {
        return $this->view($user, $release);
    }

    public function restore(User $user, Release $release): Response
    {
        return $this->view($user, $release);
    }

    public function forceDelete(User $user, Release $release): Response
    {
        return $this->view($user, $release);
    }

    private function accessResponse(bool $allowed): Response
    {
        return $allowed ? Response::allow() : Response::denyAsNotFound();
    }
}
