<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Database\Seeder;

class ReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::query()
            ->with('owner')
            ->inRandomOrder()
            ->get()
            ->each(function (Project $project): void {
                $release = Release::factory()
                    ->for($project)
                    ->for($project->owner, 'user')
                    ->create();

                $project->tickets()
                    ->whereNull('release_id')
                    ->inRandomOrder()
                    ->limit(5)
                    ->update(['release_id' => $release->id]);
            });
    }
}
