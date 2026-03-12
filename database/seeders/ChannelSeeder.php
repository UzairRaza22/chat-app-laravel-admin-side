<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Channel;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin, users, workspaces, and teams from other seeders
        $admin = \App\Models\Admin\Admin::where('email', 'admin@test.com')->first();
        $users = \App\Models\Admin\User::all();
        $workspaces = \App\Models\Admin\Workspace::all();
        $teams = \App\Models\Admin\Team::all();
        
        if (!$admin || $users->count() < 6 || $workspaces->count() < 7 || $teams->count() < 12) {
            $this->command->error('❌ Please run AdminSeeder, UserSeeder, WorkspaceSeeder, and TeamSeeder first!');
            return;
        }

        // Create Channels
        $channels = [];
        $channels[] = Channel::create([
            'name' => 'general',
            'description' => 'General discussion channel',
            'workspace_id' => (string)$workspaces[0]->_id,
            'team_id' => (string)$teams[0]->_id,
            'type' => 'public',
            'creator_id' => (string)$users[0]->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[1]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'development',
            'description' => 'Development discussions',
            'workspace_id' => (string)$workspaces[0]->_id,
            'team_id' => (string)$teams[1]->_id,
            'type' => 'private',
            'creator_id' => (string)$users[0]->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[2]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'marketing-ideas',
            'description' => 'Marketing campaign ideas',
            'workspace_id' => (string)$workspaces[1]->_id,
            'team_id' => (string)$teams[2]->_id,
            'type' => 'public',
            'creator_id' => (string)$users[1]->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[3]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'direct-messages',
            'description' => 'Direct messages between users',
            'workspace_id' => (string)$workspaces[0]->_id,
            'team_id' => null,
            'type' => 'direct',
            'creator_id' => (string)$users[0]->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[1]->_id],
            'is_active' => true,
        ]);

        $this->command->info('✅ Channels seeded successfully!');
        $this->command->info('📢 Channels:');
        $this->command->info('   - general (public)');
        $this->command->info('   - development (private)');
        $this->command->info('   - marketing-ideas (public)');
        $this->command->info('   - direct-messages (direct)');
    }
}
