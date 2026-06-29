<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Tag;
use App\Enums\Status;
use App\Models\Ticket;
use App\Enums\Priority;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TicketForm extends Component
{
    public bool $show_ticket_form = false;

    public Collection $projects;

    public ?Project $project = null;

    public ?Ticket $ticket = null;

    public ?int $project_id = null;

    public string $title = '';

    public ?string $description = null;
    
    public Priority $priority = Priority::MEDIUM;

    public ?array $project_tags = [];

    public array $tags = [];

    public ?Carbon $due_date = null;

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int'],
            'title' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'priority' => ['required', Rule::enum(Priority::class)],
            'tags' => ['nullable', 'array'],
            'due_date' => ['nullable', 'date']
        ];
    }

    public function mount(): void
    {
        if ($this->project) $this->project_id = $this->project->id;

        $this->getProjects();
        $this->getProjectTags();
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

    public function getProjectTags(): self
    {
        $this->project_tags = Project::query()
            ->find($this->project_id)
            ?->tags()
            ->select(['name', 'color'])
            ->orderBy('name')
            ->get()
            ->map(fn (Tag $tag): array => [
                'name' => $tag->name,
                'color' => $tag->color->value,
            ])
            ->all() ?? [];

        return $this;
    }

    public function updatedProjectId(): void
    {
        $this->reset(['project_tags', 'tags']);

        $this->getProjectTags();
    }

    public function updatedTags(): void
    {
        $this->tags = collect($this->tags)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    #[On('load-ticket')]
    public function loadTicket(int $ticket_id): void
    {
        $this->ticket = Ticket::with('tags')->find($ticket_id);
        $this->project_id = $this->ticket->project_id;
        $this->title = $this->ticket->title;
        $this->description = $this->ticket->description;
        $this->priority = $this->ticket->priority;
        $this->tags = $this->ticket
            ->tags
            ->pluck('name')
            ->sort()
            ->values()
            ->toArray();
        $this->due_date = $this->ticket->due_date;

        $this->getProjectTags();

        $this->ticket->trackRecentView();

        $this->show_ticket_form = true;
    }

    public function removeTag(string $tag): void
    {
        $this->tags = collect($this->tags)
            ->reject(fn (string $value): bool => $value === $tag)
            ->values()
            ->all();
    }

    public function resetForm(): void
    {
        $this->reset([
            'ticket',
            'title',
            'description',
            'priority',
            'tags',
            'due_date',
            'project_tags',
        ]);

        $this->priority = Priority::MEDIUM;

        $this->project_id = $this->project
            ? $this->project->id
            : null;

        $this->getProjectTags();
    }

    private function nextSequence(): int
    {
        return ((int) Ticket::query()
            ->where('project_id', $this->project_id)
            ->max('sequence')) + 1;
    }

    private function nextPosition(?Status $status): int
    {
        return ((int) Ticket::query()
            ->where('project_id', $this->project_id)
            ->when(
                $status,
                fn (Builder $query): Builder => $query->where('status', $status),
                fn (Builder $query): Builder => $query->whereNull('status'),
            )
            ->max('position')) + 1;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $tags = $validated['tags'] ?? [];

        unset($validated['tags']);

        $is_creating = ! $this->ticket;

        $status = $is_creating
            ? Status::TO_DO
            : $this->ticket->status;

        $ticket = Ticket::updateOrCreate(
            ['id' => $this->ticket?->id],
            [
                ...$validated,
                'project_id' => $this->project_id,
                'user_id' => auth()->id(),
                'status' => $status,
                'sequence' => $is_creating
                    ? $this->nextSequence()
                    : $this->ticket->sequence,
                'position' => $is_creating
                    ? $this->nextPosition($status)
                    : $this->ticket->position,
            ],
        );

        $tag_ids = Project::query()
            ->find($this->project_id)
            ?->tags()
            ->whereIn('name', array_filter($tags))
            ->pluck('id')
            ->all() ?? [];

        $ticket->tags()->sync($tag_ids);

        $this->redirectRoute(
            $this->project ? 'project.tickets' : 'tickets',
            $this->project,
        );
    }

    public function delete(): void
    {
        $this->ticket?->delete();

        $this->redirectRoute(
            $this->project ? 'project.tickets' : 'tickets',
            $this->project,
        );
    }

    public function render(): View
    {
        return view('livewire.ticket-form');
    }
}
