<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RecentView extends Model
{
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
