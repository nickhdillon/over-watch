<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Color;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Color|null $color
 */
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'color' => Color::class,
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class);
    }
}
