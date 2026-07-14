<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): Response
    {
        return $this->accessResponse($ticket->project->isAccessibleBy($user));
    }

    public function create(User $user, Project $project): Response
    {
        return $this->accessResponse($project->isAccessibleBy($user));
    }

    public function update(User $user, Ticket $ticket): Response
    {
        return $this->view($user, $ticket);
    }

    public function delete(User $user, Ticket $ticket): Response
    {
        return $this->view($user, $ticket);
    }

    public function restore(User $user, Ticket $ticket): Response
    {
        return $this->view($user, $ticket);
    }

    public function forceDelete(User $user, Ticket $ticket): Response
    {
        return $this->view($user, $ticket);
    }

    private function accessResponse(bool $allowed): Response
    {
        return $allowed ? Response::allow() : Response::denyAsNotFound();
    }
}
