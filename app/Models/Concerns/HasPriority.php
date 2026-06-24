<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Priority;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

trait HasPriority
{
    #[Scope]
    public function orderByPriority(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = Str::lower($direction) === 'desc' ? 'DESC' : 'ASC';

        return $query->orderByRaw("
            CASE priority
                WHEN '" . Priority::HIGH->value . "' THEN 1
                WHEN '" . Priority::MEDIUM->value . "' THEN 2
                WHEN '" . Priority::LOW->value . "' THEN 3
            END {$direction}
        ");
    }
}
