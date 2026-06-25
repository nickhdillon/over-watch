<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Status;
use App\Models\Ticket;
use App\Enums\Priority;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TicketForm extends Component
{
    public bool $show_ticket_form = false;

    public Collection $projects;

    public ?Project $project = null;

    public ?Ticket $ticket = null;

    public int $project_id;

    public string $title = '';

    public ?string $description = null;
    
    public Priority $priority = Priority::MEDIUM;

    public ?Carbon $due_date = null;

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int'],
            'title' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'priority' => ['required', Rule::enum(Priority::class)],
            'due_date' => ['nullable', 'date']
        ];
    }

    public function mount(): void
    {
        $this->getProjects();
    }

    public function getProjects(): self
    {
        $this->projects = auth()
            ->user()
            ->projects()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return $this;
    }

    #[On('load-ticket')]
    public function loadTicket(int $ticket_id): void
    {
        $this->ticket = Ticket::find($ticket_id);
        $this->project_id = $this->ticket->project_id;
        $this->title = $this->ticket->title;
        $this->description = $this->ticket->description;
        $this->priority = $this->ticket->priority;
        $this->due_date = $this->ticket->due_date;

        $this->show_ticket_form = true;
    }

    public function resetForm(): void
    {
        $this->reset([
            'ticket',
            'project_id',
            'title',
            'description',
            'priority',
            'due_date'
        ]);

        $this->priority = Priority::MEDIUM;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $is_creating = ! $this->ticket;

        $status = $is_creating
            ? Status::TO_DO
            : $this->ticket->status;

        Ticket::updateOrCreate(
            ['id' => $this->ticket?->id],
            [
                ...$validated,
                'project_id' => $this->project_id,
                'user_id' => auth()->id(),
                'status' => $status,
                'position' => $is_creating
                    ? $this->nextPosition($status)
                    : $this->ticket->position,
            ],
        );

        $this->redirectRoute(
            $this->project ? 'project.tickets' : 'tickets',
            $this->project,
        );
    }

    private function nextPosition(?Status $status): int
    {
        return ((int) Ticket::query()
            ->where('project_id', $this->project?->id)
            ->when(
                $status,
                fn (Builder $query): Builder => $query->where('status', $status),
                fn (Builder $query): Builder => $query->whereNull('status'),
            )
            ->max('position')) + 1;
    }

    public function delete(): void
    {
        $this->ticket?->delete();
    }

    public function render(): View
    {
        return view('livewire.ticket-form');
    }
}
