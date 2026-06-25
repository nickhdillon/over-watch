<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Color;
use App\Models\User;
use App\Enums\Priority;
use App\Models\Project;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::first()->id,
            'name' => 'Pure Finance',
            'key' => 'PFI',
            'slug' => 'pure-finance',
            'url' => 'https://pure-finance.app',
            'description' => 'Personal finance app',
            'image_path' => null,
            'color' => Arr::random(Color::cases()),
            'priority' => Arr::random(Priority::cases()),
        ];
    }
}
