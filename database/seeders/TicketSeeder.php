<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $project_tickets = [
            'Pure Finance' => [
                'Add recurring transactions',
                'Build spending report',
                'Implement account transfers',
                'Create budget overview',
                'Add CSV export'
            ],
            'Audio Archive' => [
                'Improve queue management',
                'Add playlist reordering',
                'Optimize artwork caching',
                'Implement search filters',
                'Create listening statistics'
            ],
            'Movie Vault' => [
                'Create movie details page',
                'Add movie wishlist support',
                'Implement collection statistics',
                'Build advanced search filters',
                'Add poster image uploads'
            ]
        ];

        Project::query()->each(function (Project $project) use ($user, $project_tickets): void {
            $tickets = $project_tickets[$project->name] ?? [
                'Research requirements',
                'Create initial design',
                'Implement core functionality',
                'Write automated tests',
                'Prepare first release',
            ];

            foreach ($tickets as $index => $title) {
                Ticket::factory()->create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'sequence' => $index + 1,
                    'title' => $title,
                    'priority' => match ($index) {
                        0 => Priority::HIGH,
                        1, 2 => Priority::MEDIUM,
                        default => Priority::LOW
                    },
                    'status' => match ($index) {
                        0 => Status::TO_DO,
                        1 => Status::IN_PROGRESS,
                        2 => Status::IN_REVIEW,
                        3 => Status::DONE,
                        default => Status::TO_DO
                    },
                ]);
            }
        });
    }
}
