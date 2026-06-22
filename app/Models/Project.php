<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'color' => Color::class
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

    public function user(): BelongsToMany
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
}
