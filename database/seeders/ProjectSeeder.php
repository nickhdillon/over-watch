<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::factory(3)
            ->sequence(
                [
                    'name' => 'Pure Finance',
                    'slug' => 'pure-finance',
                    'description' => 'Personal finance app'
                ],
                [
                    'name' => 'Audio Archive',
                    'slug' => 'audio-archive',
                    'description' => 'Personal audio player and library'
                ],
                [
                    'name' => 'Movie Vault',
                    'slug' => 'movie-vault',
                    'description' => 'Personal movie and tv show collection'
                ]
            )
            ->create();

        User::first()->projects()->attach(
            $projects->pluck('id')->mapWithKeys(fn (int $id): array => [
                $id => ['role' => ProjectRole::OWNER],
            ])
        );
    }
}
