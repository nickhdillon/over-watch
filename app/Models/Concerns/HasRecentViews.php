<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\RecentView;

trait HasRecentViews
{
    public function trackRecentView(): void
    {
        if (! auth()->check()) return;

        RecentView::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'viewable_type' => $this->getMorphClass(),
                'viewable_id' => $this->getKey(),
            ],
            [
                'last_viewed_at' => now(),
            ],
        );
    }
}
