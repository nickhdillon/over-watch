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
            ->for(User::first(), 'owner')
            ->sequence(
                [
                    'name' => 'Pure Finance',
                    'key' => 'PURE',
                    'slug' => 'pure-finance',
                    'url' => 'https://pure-finance.app',
                    'repository_url' => 'https://github.com/nickhdillon/pure-finance',
                    'description' => 'Personal finance app'
                ],
                [
                    'name' => 'Audio Archive',
                    'key' => 'AUD',
                    'slug' => 'audio-archive',
                    'url' => 'https://audio-archive.app',
                    'repository_url' => 'https://github.com/nickhdillon/audio-archive',
                    'description' => 'Personal audio player and library'
                ],
                [
                    'name' => 'Movie Vault',
                    'key' => 'MOV',
                    'slug' => 'movie-vault',
                    'url' => 'https://movie-vault.app',
                    'repository_url' => 'https://github.com/nickhdillon/movie-vault',
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
