<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasRecentViews;
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
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;
    use HasRecentViews;
    use HasPriority;

    public function casts(): array
    {
        return [
            'status' => Status::class,
            'priority' => Priority::class,
            'due_date' => 'date'
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
}
