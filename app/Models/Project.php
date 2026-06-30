<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Color;
use App\Enums\Priority;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasRecentViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property Color|null $color
 * @property Priority|null $priority
 */
class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    use HasRecentViews;
    use HasPriority;

    protected function casts(): array
    {
        return [
            'color' => Color::class,
            'priority' => Priority::class
        ];
    }

    public static function booted(): void
    {
        static::creating(function (self $project): void {
            $project->slug = Str::slug($project->name);
        });

        static::updating(function (self $project): void {
            if ($project->isDirty('name')) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function recentViews(): MorphMany
    {
        return $this->morphMany(RecentView::class, 'viewable');
    }

    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }
}
