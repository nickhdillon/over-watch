<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Color;
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
            'name' => 'Pure Finance',
            'slug' => 'pure-finance',
            'description' => 'Personal finance app',
            'image_path' => null,
            'color' => Arr::random(Color::cases())
        ];
    }
}
