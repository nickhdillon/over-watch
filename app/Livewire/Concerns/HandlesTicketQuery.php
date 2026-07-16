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

    #[Url(except: 'position')]
    public string $sort = 'position';

    #[Url(as: 'direction', except: 'asc')]
    public string $sort_direction = 'asc';

    public function mountHandlesTicketQuery(): void
    {
        $this->setFilters($this->filters);

        $this->normalizeTicketSort();
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

    protected function applyTicketSort(Builder|Relation $query): Builder|Relation
    {
        $query = match ($this->sort) {
            'name' => $query->orderBy('name', $this->sort_direction),
            'priority' => $query->orderByRaw(
                "case priority when 'high' then 1 when 'low' then 2 when 'medium' then 3 else 4 end {$this->sort_direction}",
            ),
            'status' => $query->orderByRaw(
                "case status when 'done' then 1 when 'in_progress' then 2 when 'in_review' then 3 when 'open' then 4 else 5 end {$this->sort_direction}",
            ),
            'project' => $query->orderBy(
                Project::query()->select('name')->whereColumn('projects.id', 'tickets.project_id'),
                $this->sort_direction,
            ),
            'key' => $query
                ->orderBy(Project::query()->select('key')->whereColumn('projects.id', 'tickets.project_id'), $this->sort_direction)
                ->orderBy('sequence', $this->sort_direction),
            default => $query->orderBy('position', $this->sort_direction),
        };

        return $query->orderBy('id');
    }

    public function updatedSort(): void
    {
        $this->normalizeTicketSort();

        $this->resetTicketSortResults();
    }

    public function updatedSortDirection(): void
    {
        $this->normalizeTicketSort();

        $this->resetTicketSortResults();
    }

    public function resetTicketSort(): void
    {
        $this->sort = 'position';
        $this->sort_direction = 'asc';

        $this->resetTicketSortResults();
    }

    public function ticketSortSummary(): ?string
    {
        if ($this->sort === 'position' && $this->sort_direction === 'asc') return null;

        $field = match ($this->sort) {
            'name' => 'Name',
            'priority' => 'Priority',
            'status' => 'Status',
            'project' => 'Project',
            'key' => 'Project key',
            default => 'Position',
        };

        $direction = in_array($this->sort, ['name', 'priority', 'status', 'project'], true)
            ? ($this->sort_direction === 'asc' ? 'A–Z' : 'Z–A')
            : ($this->sort_direction === 'asc' ? 'Asc' : 'Desc');

        return "{$field} · {$direction}";
    }

    private function resetTicketSortResults(): void
    {
        $this->resetPage();
        unset($this->tickets, $this->boardTickets, $this->ticketsByStatus);
    }

    public function ticketFilterLabel(string $filter): string
    {
        $enum = Status::tryFrom($filter) ?? Priority::tryFrom($filter);

        if ($enum) return $enum->label();

        [$type, $value] = array_pad(explode(':', $filter, 2), 2, null);

        return match ($type) {
            'tag' => $value ?? 'Tag',
            'project' => Project::query()->find($value)->name ?? 'Project',
            'release' => Release::query()->find($value)->name ?? 'Release',
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
        if (! $this->showProjectTicketFilter()) return collect();

        return auth()->user()->projects()->orderBy('name')->get();
    }

    public function ticketFilterReleases(): Collection
    {
        if (! $this->showReleaseTicketFilter()) return collect();

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

    private function allowedTicketFilters(): Collection
    {
        return $this->ticketStatusFilters()
            ->merge($this->ticketPriorityFilters())
            ->merge($this->ticketFilterTags()->map(fn (Tag $tag): string => "tag:{$tag->name}"))
            ->merge($this->ticketFilterProjects()->map(fn (Project $project): string => "project:{$project->id}"))
            ->merge($this->ticketFilterReleases()->map(fn (Release $release): string => "release:{$release->id}"));
    }

    private function ticketFilterIds(Collection $filters, string $type): Collection
    {
        return $this->ticketFilterValues($filters, $type)
            ->map(fn (string $value): int => (int) $value)
            ->filter();
    }

    private function ticketFilterValues(Collection $filters, string $type): Collection
    {
        return $filters
            ->filter(fn (string $filter): bool => str_starts_with($filter, "{$type}:"))
            ->map(fn (string $filter): string => str($filter)->after(':')->toString());
    }

    private function ticketStatusFilters(): Collection
    {
        return collect(Status::cases())->map(fn (Status $status): string => $status->value);
    }

    private function ticketPriorityFilters(): Collection
    {
        return collect(Priority::cases())->map(fn (Priority $priority): string => $priority->value);
    }

    private function normalizeTicketSort(): void
    {
        if (! in_array($this->sort, ['position', 'name', 'priority', 'status', 'project', 'key'], true)) {
            $this->sort = 'position';
        }

        if (! in_array($this->sort_direction, ['asc', 'desc'], true)) {
            $this->sort_direction = 'asc';
        }
    }

    private function resetTicketFilterResults(): void
    {
        $this->resetPage();
        unset($this->tickets, $this->boardTickets, $this->ticketsByStatus);
    }
}
