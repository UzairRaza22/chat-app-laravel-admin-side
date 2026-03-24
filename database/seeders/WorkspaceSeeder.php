<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Workspace;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin and users from other seeders
        $admin = \App\Models\Admin\Admin::where('email', 'admin@test.com')->first();
        $users = \App\Models\Admin\User::all();
        
        if (!$admin || $users->count() < 6) {
            $this->command->error('❌ Please run AdminSeeder and UserSeeder first!');
        }

        // Create Workspaces - Each user creates multiple workspaces
        $workspaces = [];
        
        // John's workspaces
        $workspaces[] = Workspace::create([
            'name' => 'John\'s Tech Startup',
            'description' => 'Main workspace for John\'s tech startup company',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[1]->_id, (string)$users[2]->_id],
        ]);

        $workspaces[] = Workspace::create([
            'name' => 'John\'s Side Project',
            'description' => 'Personal side project workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[0]->_id, (string)$users[4]->_id],
        ]);

        // Jane's workspaces
        $workspaces[] = Workspace::create([
            'name' => 'Jane\'s Marketing Agency',
            'description' => 'Marketing agency workspace managed by Jane',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[2]->_id, (string)$users[3]->_id],
        ]);

        $workspaces[] = Workspace::create([
            'name' => 'Jane\'s Creative Studio',
            'description' => 'Creative design and content workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[1]->_id, (string)$users[4]->_id, (string)$users[5]->_id],
        ]);

        // Mike's workspaces
        $workspaces[] = Workspace::create([
            'name' => 'Mike\'s Development Hub',
            'description' => 'Full-stack development workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[2]->_id, (string)$users[0]->_id, (string)$users[4]->_id],
        ]);

        // Sarah's workspace
        $workspaces[] = Workspace::create([
            'name' => 'Sarah\'s Consulting Firm',
            'description' => 'Business consulting workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[3]->_id, (string)$users[1]->_id, (string)$users[5]->_id],
        ]);

        // Alex's workspace
        $workspaces[] = Workspace::create([
            'name' => 'Alex\'s Innovation Lab',
            'description' => 'Research and innovation workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$users[4]->_id, (string)$users[0]->_id, (string)$users[2]->_id],
        ]);

        // Update users with their workspace IDs
        $users[0]->update(['workspace_ids' => [(string)$workspaces[0]->_id, (string)$workspaces[1]->_id, (string)$workspaces[4]->_id, (string)$workspaces[6]->_id]]);
        $users[1]->update(['workspace_ids' => [(string)$workspaces[0]->_id, (string)$workspaces[2]->_id, (string)$workspaces[3]->_id, (string)$workspaces[5]->_id]]);
        $users[2]->update(['workspace_ids' => [(string)$workspaces[0]->_id, (string)$workspaces[2]->_id, (string)$workspaces[4]->_id, (string)$workspaces[6]->_id]]);
        $users[3]->update(['workspace_ids' => [(string)$workspaces[2]->_id, (string)$workspaces[5]->_id]]);
        $users[4]->update(['workspace_ids' => [(string)$workspaces[1]->_id, (string)$workspaces[3]->_id, (string)$workspaces[4]->_id, (string)$workspaces[6]->_id]]);
        $users[5]->update(['workspace_ids' => [(string)$workspaces[3]->_id, (string)$workspaces[5]->_id]]);

        $this->command->info('✅ Workspaces seeded successfully!');
        $this->command->info('🏢 Workspaces:');
        $this->command->info('   - John\'s Tech Startup');
        $this->command->info('   - John\'s Side Project');
        $this->command->info('   - Jane\'s Marketing Agency');
        $this->command->info('   - Jane\'s Creative Studio');
        $this->command->info('   - Mike\'s Development Hub');
        $this->command->info('   - Sarah\'s Consulting Firm');
        $this->command->info('   - Alex\'s Innovation Lab');
    }
}
