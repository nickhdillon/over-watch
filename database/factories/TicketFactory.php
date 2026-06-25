<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'sequence' => 1,
            'title' => 'Test ticket',
            'description' => 'This is a test ticket',
            'status' => Arr::random(Status::cases()),
            'priority' => Arr::random(Priority::cases()),
            'position' => 0,
            'due_date' => null,
            'completed_at' => null
        ];
    }
}
