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
                    'url' => 'https://pure-finance.app',
                    'description' => 'Personal finance app'
                ],
                [
                    'name' => 'Audio Archive',
                    'slug' => 'audio-archive',
                    'url' => 'https://audio-archive.app',
                    'description' => 'Personal audio player and library'
                ],
                [
                    'name' => 'Movie Vault',
                    'slug' => 'movie-vault',
                    'url' => 'https://movie-vault.app',
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
