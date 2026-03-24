<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin, users, and workspaces from other seeders
        $admin = \App\Models\Admin\Admin::where('email', 'admin@test.com')->first();
        $users = \App\Models\Admin\User::all();
        $workspaces = \App\Models\Admin\Workspace::all();
        
        if (!$admin || $users->count() < 6 || $workspaces->count() < 7) {
            $this->command->error('❌ Please run AdminSeeder, UserSeeder, and WorkspaceSeeder first!');
            return;
        }

        // Create Teams - Multiple teams per workspace with different creators
        $teams = [];
        
        // Teams for John's Tech Startup
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'name' => 'Frontend Development',
            'description' => 'Frontend development team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[1]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'name' => 'Backend Development',
            'description' => 'Backend development team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[2]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'name' => 'QA Testing',
            'description' => 'Quality assurance and testing team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[2]->_id],
            'is_active' => true,
        ]);

        // Teams for John's Side Project
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[1]->_id,
            'name' => 'Core Development',
            'description' => 'Core development team for side project',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[4]->_id],
            'is_active' => true,
        ]);

        // Teams for Jane's Marketing Agency
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[2]->_id,
            'name' => 'Digital Marketing',
            'description' => 'Digital marketing and social media team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[3]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[2]->_id,
            'name' => 'Content Creation',
            'description' => 'Content creation and copywriting team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[2]->_id],
            'is_active' => true,
        ]);

        // Teams for Jane's Creative Studio
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[3]->_id,
            'name' => 'Design Team',
            'description' => 'Creative design and UI/UX team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[4]->_id, (string)$users[5]->_id],
            'is_active' => true,
        ]);

        // Teams for Mike's Development Hub
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[4]->_id,
            'name' => 'Full Stack Team',
            'description' => 'Full-stack development team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[2]->_id, (string)$users[0]->_id, (string)$users[4]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[4]->_id,
            'name' => 'DevOps Team',
            'description' => 'DevOps and infrastructure team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[2]->_id, (string)$users[4]->_id],
            'is_active' => true,
        ]);

        // Teams for Sarah's Consulting Firm
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[5]->_id,
            'name' => 'Business Strategy',
            'description' => 'Business strategy and consulting team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[3]->_id, (string)$users[1]->_id],
            'is_active' => true,
        ]);

        // Teams for Alex's Innovation Lab
        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[6]->_id,
            'name' => 'Research Team',
            'description' => 'Research and development team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[4]->_id, (string)$users[0]->_id, (string)$users[2]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => (string)$workspaces[6]->_id,
            'name' => 'Innovation Team',
            'description' => 'Innovation and prototyping team',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[4]->_id, (string)$users[2]->_id],
            'is_active' => false, // Inactive team for testing
        ]);

        // Update users with their team IDs
        $users[0]->update(['team_ids' => [(string)$teams[0]->_id, (string)$teams[1]->_id, (string)$teams[3]->_id, (string)$teams[7]->_id, (string)$teams[10]->_id]]);
        $users[1]->update(['team_ids' => [(string)$teams[0]->_id, (string)$teams[2]->_id, (string)$teams[4]->_id, (string)$teams[5]->_id, (string)$teams[6]->_id, (string)$teams[9]->_id]]);
        $users[2]->update(['team_ids' => [(string)$teams[1]->_id, (string)$teams[2]->_id, (string)$teams[5]->_id, (string)$teams[7]->_id, (string)$teams[8]->_id, (string)$teams[10]->_id, (string)$teams[11]->_id]]);
        $users[3]->update(['team_ids' => [(string)$teams[4]->_id, (string)$teams[9]->_id]]);
        $users[4]->update(['team_ids' => [(string)$teams[3]->_id, (string)$teams[6]->_id, (string)$teams[7]->_id, (string)$teams[8]->_id, (string)$teams[10]->_id, (string)$teams[11]->_id]]);
        $users[5]->update(['team_ids' => [(string)$teams[6]->_id]]);

        $this->command->info('✅ Teams seeded successfully!');
        $this->command->info('👥 Teams:');
        $this->command->info('   - Frontend Development (Active)');
        $this->command->info('   - Backend Development (Active)');
        $this->command->info('   - QA Testing (Active)');
        $this->command->info('   - Core Development (Active)');
        $this->command->info('   - Digital Marketing (Active)');
        $this->command->info('   - Content Creation (Active)');
        $this->command->info('   - Design Team (Active)');
        $this->command->info('   - Full Stack Team (Active)');
        $this->command->info('   - DevOps Team (Active)');
        $this->command->info('   - Business Strategy (Active)');
        $this->command->info('   - Research Team (Active)');
        $this->command->info('   - Innovation Team (Inactive)');
    }
}
