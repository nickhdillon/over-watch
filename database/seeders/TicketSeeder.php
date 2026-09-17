<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'Add CSV export',
            ],
            'Audio Archive' => [
                'Improve queue management',
                'Add playlist reordering',
                'Optimize artwork caching',
                'Implement search filters',
                'Create listening statistics',
            ],
            'Movie Vault' => [
                'Create movie details page',
                'Add movie wishlist support',
                'Implement collection statistics',
                'Build advanced search filters',
                'Add poster image uploads',
            ],
            'Website Redesign' => [
                ['name' => 'Refresh homepage hero section', 'status' => Status::OPEN, 'priority' => Priority::HIGH],
                ['name' => 'Finalize brand messaging', 'status' => Status::OPEN, 'priority' => Priority::MEDIUM],
                ['name' => 'Design customer story pages', 'status' => Status::OPEN, 'priority' => Priority::MEDIUM],
                ['name' => 'Build responsive layout', 'status' => Status::DONE, 'priority' => Priority::MEDIUM],
                ['name' => 'Create product feature overview', 'status' => Status::OPEN, 'priority' => Priority::LOW],
                ['name' => 'Add customer testimonials', 'status' => Status::IN_PROGRESS, 'priority' => Priority::HIGH],
                ['name' => 'Improve pricing page clarity', 'status' => Status::IN_REVIEW, 'priority' => Priority::MEDIUM],
                ['name' => 'Optimize signup flow', 'status' => Status::DONE, 'priority' => Priority::LOW],
                ['name' => 'Create reusable content sections', 'status' => Status::OPEN, 'priority' => Priority::LOW],
                ['name' => 'Add product analytics events', 'status' => Status::IN_PROGRESS, 'priority' => Priority::LOW],
                ['name' => 'Improve mobile page performance', 'status' => Status::OPEN, 'priority' => Priority::HIGH],
                ['name' => 'Review search experience', 'status' => Status::DONE, 'priority' => Priority::LOW],
                ['name' => 'Run accessibility audit', 'status' => Status::OPEN, 'priority' => Priority::MEDIUM],
                ['name' => 'Complete cross-browser testing', 'status' => Status::IN_PROGRESS, 'priority' => Priority::LOW],
                ['name' => 'Prepare website launch checklist', 'status' => Status::IN_REVIEW, 'priority' => Priority::LOW],
            ],
        ];

        Project::query()->each(function (Project $project) use ($user, $project_tickets): void {
            $tickets = $project_tickets[$project->name] ?? [
                'Research requirements',
                'Create initial design',
                'Implement core functionality',
                'Write automated tests',
                'Prepare first release',
            ];

            $status_positions = [];

            foreach ($tickets as $index => $ticket) {
                $name = is_array($ticket) ? $ticket['name'] : $ticket;
                $status = is_array($ticket) ? $ticket['status'] : match ($index) {
                    0 => Status::OPEN,
                    1 => Status::IN_PROGRESS,
                    2 => Status::IN_REVIEW,
                    3 => Status::DONE,
                    default => Status::OPEN,
                };
                $priority = is_array($ticket) ? $ticket['priority'] : match ($index) {
                    0 => Priority::HIGH,
                    1, 2 => Priority::MEDIUM,
                    default => Priority::LOW,
                };
                $position = $status_positions[$status->value] ?? 0;
                $status_positions[$status->value] = $position + 1;

                Ticket::factory()->create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'sequence' => $index + 1,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'priority' => $priority,
                    'status' => $status,
                    'position' => $position,
                    'completed_at' => $status === Status::DONE ? now() : null,
                ]);
            }
        });
    }
}
