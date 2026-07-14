<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->project->isAccessibleBy($user);
    }

    public function create(User $user, Project $project): bool
    {
        return $project->isAccessibleBy($user);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }
}
