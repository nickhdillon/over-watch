<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Models\Concerns\HasRecentViews;
use Database\Factories\ReleaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property Carbon|null $start_date
 * @property Carbon|null $due_date
 * @property Status $status
 */
class Release extends Model
{
    /** @use HasFactory<ReleaseFactory> */
    use HasFactory;

    use HasRecentViews;

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public static function booted(): void
    {
        static::creating(function (self $release): void {
            $release->slug = Str::slug($release->name);
        });

        static::updating(function (self $release): void {
            if ($release->isDirty('name')) {
                $release->slug = Str::slug($release->name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
