<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['release_id', 'user_id', 'position']);
            $table->index(['release_id', 'user_id', 'status', 'position']);
            $table->index(['release_id', 'user_id', 'priority', 'position']);
            $table->index(['release_id', 'user_id', 'name']);
            $table->index(['user_id', 'name']);
            $table->index(['user_id', 'status', 'position']);
            $table->index(['user_id', 'priority', 'position']);
            $table->index(['user_id', 'project_id', 'position']);
            $table->index(['project_id', 'name']);
            $table->index(['project_id', 'priority', 'position']);
        });

        Schema::table('tag_ticket', function (Blueprint $table) {
            $table->index(['ticket_id', 'tag_id']);
        });

        Schema::table('project_user', function (Blueprint $table) {
            $table->index(['user_id', 'project_id']);
        });

        Schema::table('releases', function (Blueprint $table) {
            $table->index(['user_id', 'name']);
            $table->index(['project_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['release_id', 'user_id', 'position']);
            $table->dropIndex(['release_id', 'user_id', 'status', 'position']);
            $table->dropIndex(['release_id', 'user_id', 'priority', 'position']);
            $table->dropIndex(['release_id', 'user_id', 'name']);
            $table->dropIndex(['user_id', 'name']);
            $table->dropIndex(['user_id', 'status', 'position']);
            $table->dropIndex(['user_id', 'priority', 'position']);
            $table->dropIndex(['user_id', 'project_id', 'position']);
            $table->dropIndex(['project_id', 'name']);
            $table->dropIndex(['project_id', 'priority', 'position']);
        });

        Schema::table('tag_ticket', function (Blueprint $table) {
            $table->dropIndex(['ticket_id', 'tag_id']);
        });

        Schema::table('project_user', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'project_id']);
        });

        Schema::table('releases', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'name']);
            $table->dropIndex(['project_id', 'name']);
        });
    }
};
