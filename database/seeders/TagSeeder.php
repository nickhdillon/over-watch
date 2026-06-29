<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags_by_project = [
            [
                ['name' => 'Bug', 'color' => 'red'],
                ['name' => 'Feature', 'color' => 'blue'],
                ['name' => 'Performance', 'color' => 'amber'],
                ['name' => 'Technical Debt', 'color' => 'neutral'],
            ],
            [
                ['name' => 'Design', 'color' => 'purple'],
                ['name' => 'Content', 'color' => 'green'],
                ['name' => 'SEO', 'color' => 'sky'],
                ['name' => 'Accessibility', 'color' => 'pink'],
            ],
            [
                ['name' => 'iOS', 'color' => 'neutral'],
                ['name' => 'Android', 'color' => 'emerald'],
                ['name' => 'Notifications', 'color' => 'orange'],
                ['name' => 'Testing', 'color' => 'cyan'],
            ],
        ];

        Project::query()
            ->with('tickets')
            ->get()
            ->each(function (Project $project, int $index) use ($tags_by_project): void {
                $tags = collect($tags_by_project[$index] ?? $tags_by_project[0])
                    ->map(fn (array $tag): Tag => Tag::query()->create([
                        'project_id' => $project->id,
                        'name' => $tag['name'],
                        'color' => $tag['color'],
                    ]));

                $project->tickets->each(function ($ticket) use ($tags): void {
                    /** @var Ticket $ticket */
                    
                    $ticket->tags()->attach(
                        $tags->random(rand(1, 3))->pluck('id')->all()
                    );
                });
            });
    }
}
