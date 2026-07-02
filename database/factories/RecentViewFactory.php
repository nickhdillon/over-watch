<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Project;
use App\Models\RecentView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecentView>
 */
class RecentViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'viewable_id' => Project::factory(),
            'viewable_type' => Project::class,
            'last_viewed_at' => now()
        ];
    }
}
