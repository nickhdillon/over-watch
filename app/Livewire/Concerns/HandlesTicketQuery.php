<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Release;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;

trait HandlesTicketQuery
{
    #[Url(as: 'filter', except: [])]
    public array $filters = [];

    public array $draft_filters = [];

    public function mountHandlesTicketQuery(): void
    {
        $this->setFilters($this->filters);
    }

    public function syncDraftFilters(): void
    {
        $this->draft_filters = $this->filters;
    }

    public function applyFilters(): void
    {
        $this->setFilters($this->draft_filters);

        $this->resetTicketFilterResults();
    }

    public function removeFilter(string $filter): void
    {
        $this->setFilters(
            collect($this->filters)
                ->reject(fn (string $active_filter): bool => $active_filter === $filter)
                ->all(),
        );

        $this->resetTicketFilterResults();
    }

    public function clearFilters(): void
    {
        $this->setFilters([]);

        $this->resetTicketFilterResults();
    }

    /**
     * @template TQuery of Builder|Relation
     *
     * @param  TQuery  $query
     * @return TQuery
     */
    protected function applyTicketFilters(Builder|Relation $query): Builder|Relation
    {
        $filters = collect($this->filters);
        $statuses = $filters->intersect($this->ticketStatusFilters());
        $priorities = $filters->intersect($this->ticketPriorityFilters());
        $tag_names = $this->ticketFilterValues($filters, 'tag');
        $project_ids = $this->ticketFilterIds($filters, 'project');
        $release_ids = $this->ticketFilterIds($filters, 'release');

        return $query
            ->when($statuses->isNotEmpty(), fn (Builder $query): Builder => $query->whereIn('status', $statuses))
            ->when($priorities->isNotEmpty(), fn (Builder $query): Builder => $query->whereIn('priority', $priorities))
            ->when($tag_names->isNotEmpty(), fn (Builder $query): Builder => $query->whereHas(
                'tags',
                fn (Builder $query): Builder => $query->whereIn('name', $tag_names),
            ))
            ->when($project_ids->isNotEmpty(), fn (Builder $query): Builder => $query->whereIn('project_id', $project_ids))
            ->when($release_ids->isNotEmpty(), fn (Builder $query): Builder => $query->whereIn('release_id', $release_ids));
    }

    public function ticketFilterLabel(string $filter): string
    {
        $enum = Status::tryFrom($filter) ?? Priority::tryFrom($filter);

        if ($enum) {
            return $enum->label();
        }

        [$type, $value] = array_pad(explode(':', $filter, 2), 2, null);

        return match ($type) {
            'tag' => $value ?? 'Tag',
            'project' => Project::query()->find($value)?->name ?? 'Project',
            'release' => Release::query()->find($value)?->name ?? 'Release',
            default => $filter,
        };
    }

    public function showProjectTicketFilter(): bool
    {
        return ! isset($this->project) && ! isset($this->release);
    }

    public function showReleaseTicketFilter(): bool
    {
        return ! isset($this->release);
    }

    public function ticketFilterProjects(): Collection
    {
        if (! $this->showProjectTicketFilter()) {
            return collect();
        }

        return auth()->user()->projects()->orderBy('name')->get();
    }

    public function ticketFilterReleases(): Collection
    {
        if (! $this->showReleaseTicketFilter()) {
            return collect();
        }

        return isset($this->project)
            ? $this->project->releases()->orderBy('name')->get()
            : auth()->user()->releases()->orderBy('name')->get();
    }

    public function ticketFilterTags(): Collection
    {
        if (isset($this->release)) {
            return $this->release->project->tags()->orderBy('name')->get()->unique('name')->values();
        }

        return isset($this->project)
            ? $this->project->tags()->orderBy('name')->get()->unique('name')->values()
            : Tag::query()
                ->whereIn('project_id', auth()->user()->projects()->select('projects.id'))
                ->orderBy('name')
                ->get()
                ->unique('name')
                ->values();
    }

    /** @param array<int, mixed> $filters */
    private function setFilters(array $filters): void
    {
        $this->filters = collect($filters)
            ->filter(fn (mixed $filter): bool => is_string($filter))
            ->intersect($this->allowedTicketFilters())
            ->unique()
            ->values()
            ->all();

        $this->draft_filters = $this->filters;
    }

    /** @return Collection<int, string> */
    private function allowedTicketFilters(): Collection
    {
        return $this->ticketStatusFilters()
            ->merge($this->ticketPriorityFilters())
            ->merge($this->ticketFilterTags()->map(fn (Tag $tag): string => "tag:{$tag->name}"))
            ->merge($this->ticketFilterProjects()->map(fn (Project $project): string => "project:{$project->id}"))
            ->merge($this->ticketFilterReleases()->map(fn (Release $release): string => "release:{$release->id}"));
    }

    /** @param Collection<int, string> $filters */
    private function ticketFilterIds(Collection $filters, string $type): Collection
    {
        return $this->ticketFilterValues($filters, $type)
            ->map(fn (string $value): int => (int) $value)
            ->filter();
    }

    /** @param Collection<int, string> $filters */
    private function ticketFilterValues(Collection $filters, string $type): Collection
    {
        return $filters
            ->filter(fn (string $filter): bool => str_starts_with($filter, "{$type}:"))
            ->map(fn (string $filter): string => str($filter)->after(':')->toString());
    }

    /** @return Collection<int, string> */
    private function ticketStatusFilters(): Collection
    {
        return collect(Status::cases())->map(fn (Status $status): string => $status->value);
    }

    /** @return Collection<int, string> */
    private function ticketPriorityFilters(): Collection
    {
        return collect(Priority::cases())->map(fn (Priority $priority): string => $priority->value);
    }

    private function resetTicketFilterResults(): void
    {
        $this->resetPage();
        unset($this->tickets, $this->boardTickets, $this->ticketsByStatus);
    }
}
