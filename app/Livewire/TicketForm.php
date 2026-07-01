<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use App\Models\Tag;
use App\Enums\Status;
use App\Models\Ticket;
use App\Enums\Priority;
use App\Models\Project;
use App\Models\Release;
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

    public string $view = 'list';

    public Collection $projects;

    public ?Project $project = null;

    public ?Ticket $ticket = null;

    public ?Release $release = null;

    public ?int $project_id = null;

    public string $name = '';

    public ?string $description = null;
    
    public Priority $priority = Priority::MEDIUM;

    public ?array $project_tags = [];

    public array $tags = [];

    public ?Carbon $due_date = null;

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int'],
            'name' => ['string', 'required'],
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
            ->map(function ($tag): array {
                /** @var Tag $tag */

                return [
                    'name' => $tag->name,
                    'color' => $tag->color->value,
                ];
            })
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
        $this->name = $this->ticket->name;
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
            'name',
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

    public function removeFromRelease(): void
    {
        if (! $this->ticket) return;

        $this->ticket->update(['release_id' => null]);

        Flux::toast('Removed ticket from release.', variant: 'success');

        $this->redirectAfterAction();
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

    private function redirectAfterAction(): void
    {
        $parameters = array_filter([
            'project' => $this->project,
            'release' => $this->release,
            'view' => $this->view,
        ]);

        if ($this->release) {
            if (! $this->project) {
                $this->project = Project::find($this->project_id);
            }

            $this->redirectRoute(
                'project.release.view',
                $parameters,
                navigate: true,
            );

            return;
        }

        $this->redirectRoute(
            $this->project ? 'project.tickets' : 'tickets',
            $parameters,
            navigate: true,
        );
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
                'release_id' => $this->release?->id,
                'user_id' => $is_creating ? auth()->id() : $this->ticket->user_id,
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

        $this->redirectAfterAction();
    }

    public function delete(): void
    {
        $this->ticket?->delete();

        $this->redirectAfterAction();
    }

    public function render(): View
    {
        return view('livewire.ticket-form');
    }
}
