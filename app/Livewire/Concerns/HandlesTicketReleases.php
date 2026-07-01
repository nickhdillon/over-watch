<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Flux\Flux;
use App\Models\Ticket;
use Illuminate\Support\Str;

trait HandlesTicketReleases
{
    public function addTicketsToRelease(array $ticket_ids, int|string $release_id, string $release_name): void
    {
        Ticket::query()
            ->whereIn('id', $ticket_ids)
            ->update(['release_id' => (int) $release_id]);

        $count = count($ticket_ids);
        $tickets = Str::plural('ticket', $count);

        Flux::toast("Added {$count} {$tickets} to the {$release_name} release.", variant: 'success');
    }

    public function removeTicketsFromRelease(array $ticket_ids): void
    {
        Ticket::query()
            ->whereIn('id', $ticket_ids)
            ->update(['release_id' => null]);

        $count = count($ticket_ids);
        $tickets = Str::plural('ticket', $count);

        Flux::toast("Removed {$count} {$tickets} from their release.", variant: 'success');
    }
}
