<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): Response
    {
        return $this->accessResponse($project->isAccessibleBy($user));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): Response
    {
        return $this->view($user, $project);
    }

    public function delete(User $user, Project $project): Response
    {
        return $this->accessResponse($project->owner_id === $user->id);
    }

    public function restore(User $user, Project $project): Response
    {
        return $this->accessResponse($project->owner_id === $user->id);
    }

    public function forceDelete(User $user, Project $project): Response
    {
        return $this->accessResponse($project->owner_id === $user->id);
    }

    private function accessResponse(bool $allowed): Response
    {
        return $allowed ? Response::allow() : Response::denyAsNotFound();
    }
}
