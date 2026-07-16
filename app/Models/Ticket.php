<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasRecentViews;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property Project $project
 * @property Carbon|null $due_date
 * @property Status|null $status
 * @property Priority|null $priority
 */
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    use HasPriority;
    use HasRecentViews;

    public function casts(): array
    {
        return [
            'status' => Status::class,
            'priority' => Priority::class,
            'due_date' => 'date',
        ];
    }

    public static function booted(): void
    {
        static::creating(function (self $ticket): void {
            $ticket->slug = Str::slug($ticket->name);
        });

        static::updating(function (self $ticket): void {
            if ($ticket->isDirty('name')) {
                $ticket->slug = Str::slug($ticket->name);
            }
        });
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function recentViews(): MorphMany
    {
        return $this->morphMany(RecentView::class, 'viewable');
    }

    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class);
    }

    protected function issueKey(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->project->key}-{$this->sequence}",
        );
    }

    #[Scope]
    protected function search(Builder $query, string $search, bool $include_tags = false) : Builder
    {
        $search = trim($search);

        if ($search === '') return $query;

        $normalized_key = strtoupper($search);

        return $query->where(function (Builder $query) use ($search, $normalized_key, $include_tags): void {
            $query
                ->where('name', 'like', "%{$search}%")
                ->orWhereHas(
                    'assignee',
                    fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%"),
                )
                ->orWhereHas(
                    'project',
                    fn (Builder $query): Builder => $query->where('key', $normalized_key),
                );

            if ($include_tags) {
                $query->orWhereHas(
                    'tags',
                    fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%"),
                );
            }

            if (preg_match('/^([a-z0-9]+)-(\d+)$/i', $search, $matches)) {
                $project_key = strtoupper($matches[1]);
                $sequence = (int) $matches[2];

                $query->orWhere(function (Builder $query) use ($project_key, $sequence): void {
                    $query
                        ->where('sequence', $sequence)
                        ->whereHas(
                            'project',
                            fn (Builder $query): Builder => $query->where('key', $project_key),
                        );
                });
            }
        });
    }
}
