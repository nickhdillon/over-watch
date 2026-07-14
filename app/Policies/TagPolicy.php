<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tag $tag): Response
    {
        return $this->accessResponse($tag->project->isAccessibleBy($user));
    }

    public function create(User $user, Project $project): Response
    {
        return $this->accessResponse($project->isAccessibleBy($user));
    }

    public function update(User $user, Tag $tag): Response
    {
        return $this->view($user, $tag);
    }

    public function delete(User $user, Tag $tag): Response
    {
        return $this->view($user, $tag);
    }

    public function restore(User $user, Tag $tag): Response
    {
        return $this->view($user, $tag);
    }

    public function forceDelete(User $user, Tag $tag): Response
    {
        return $this->view($user, $tag);
    }

    private function accessResponse(bool $allowed): Response
    {
        return $allowed ? Response::allow() : Response::denyAsNotFound();
    }
}
