<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends Factory<Release>
 */
class ReleaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'MVP',
            'Beta',
            'v1.0',
            'v2.0',
            'Launch',
        ]);

        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'start_date' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'status' => Arr::random(Status::cases()),
        ];
    }
}
