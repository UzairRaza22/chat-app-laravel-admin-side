<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Admin;
use App\Models\Admin\User;
use App\Models\Admin\Workspace;
use App\Models\Admin\Team;
use App\Models\Admin\Channel;
use App\Models\Admin\Message;
use Illuminate\Support\Facades\Hash;

class AdminTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Create Users
        $users = [];
        $users[] = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $users[] = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $users[] = User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $users[] = User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah@test.com',
            'password' => Hash::make('password123'),
            'is_active' => false, // Inactive user for testing
        ]);

        // Create Workspaces
        $workspaces = [];
        $workspaces[] = Workspace::create([
            'name' => 'Tech Company Workspace',
            'description' => 'Main workspace for tech company',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[1]->_id, $users[2]->_id],
        ]);

        $workspaces[] = Workspace::create([
            'name' => 'Marketing Team Workspace',
            'description' => 'Workspace for marketing activities',
            'creator_id' => $users[1]->_id,
            'user_ids' => [$users[1]->_id, $users[2]->_id, $users[3]->_id],
        ]);

        $workspaces[] = Workspace::create([
            'name' => 'Development Workspace',
            'description' => 'Workspace for development team',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[2]->_id],
        ]);

        // Create Teams
        $teams = [];
        $teams[] = Team::create([
            'workspace_id' => $workspaces[0]->_id,
            'name' => 'Frontend Team',
            'description' => 'Frontend development team',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[1]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => $workspaces[0]->_id,
            'name' => 'Backend Team',
            'description' => 'Backend development team',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[2]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => $workspaces[1]->_id,
            'name' => 'Marketing Team',
            'description' => 'Marketing and promotion team',
            'creator_id' => $users[1]->_id,
            'user_ids' => [$users[1]->_id, $users[3]->_id],
            'is_active' => true,
        ]);

        $teams[] = Team::create([
            'workspace_id' => $workspaces[2]->_id,
            'name' => 'DevOps Team',
            'description' => 'DevOps and infrastructure team',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[2]->_id],
            'is_active' => false, // Inactive team for testing
        ]);

        // Create Channels
        $channels = [];
        $channels[] = Channel::create([
            'name' => 'general',
            'description' => 'General discussion channel',
            'workspace_id' => $workspaces[0]->_id,
            'team_id' => $teams[0]->_id,
            'type' => 'public',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[1]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'development',
            'description' => 'Development discussions',
            'workspace_id' => $workspaces[0]->_id,
            'team_id' => $teams[1]->_id,
            'type' => 'private',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[2]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'marketing-ideas',
            'description' => 'Marketing campaign ideas',
            'workspace_id' => $workspaces[1]->_id,
            'team_id' => $teams[2]->_id,
            'type' => 'public',
            'creator_id' => $users[1]->_id,
            'user_ids' => [$users[1]->_id, $users[3]->_id],
            'is_active' => true,
        ]);

        $channels[] = Channel::create([
            'name' => 'direct-messages',
            'description' => 'Direct messages between users',
            'workspace_id' => $workspaces[0]->_id,
            'team_id' => null,
            'type' => 'direct',
            'creator_id' => $users[0]->_id,
            'user_ids' => [$users[0]->_id, $users[1]->_id],
            'is_active' => true,
        ]);

        // Create Messages
        $messages = [];
        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[0]->_id,
            'user_id' => $users[0]->_id,
            'content' => 'Welcome to the general channel! Let\'s start collaborating.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[0]->_id,
            'user_id' => $users[1]->_id,
            'content' => 'Thanks for setting this up! Looking forward to working together.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[1]->_id,
            'user_id' => $users[0]->_id,
            'content' => 'Let\'s discuss the new API endpoints here.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[1]->_id,
            'user_id' => $users[2]->_id,
            'content' => 'I\'ve uploaded the API documentation.',
            'message_type' => 'file',
            'file_name' => 'api-docs.pdf',
            'file_path' => '/uploads/api-docs.pdf',
            'file_mime' => 'application/pdf',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[1]->_id,
            'channel_id' => $channels[2]->_id,
            'user_id' => $users[1]->_id,
            'content' => 'What do you think about the new marketing campaign?',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[1]->_id,
            'channel_id' => $channels[2]->_id,
            'user_id' => $users[3]->_id,
            'content' => 'I think it needs more focus on social media.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[3]->_id,
            'user_id' => $users[0]->_id,
            'content' => 'Hey, can we discuss the project timeline privately?',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[3]->_id,
            'user_id' => $users[1]->_id,
            'content' => 'Sure! I think we can finish it by next week.',
            'message_type' => 'text',
        ]);

        // Reply message
        $messages[] = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[0]->_id,
            'user_id' => $users[2]->_id,
            'content' => 'Great initiative! Count me in.',
            'message_type' => 'text',
            'parent_message_id' => $messages[0]->_id, // Reply to first message
        ]);

        // Edited message
        $editedMessage = Message::create([
            'workspace_id' => $workspaces[0]->_id,
            'channel_id' => $channels[0]->_id,
            'user_id' => $users[1]->_id,
            'content' => 'This message has been edited for clarity.',
            'message_type' => 'text',
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        $this->command->info('✅ Admin test data seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 1 Admin');
        $this->command->info('   - 4 Users (3 active, 1 inactive)');
        $this->command->info('   - 3 Workspaces');
        $this->command->info('   - 4 Teams (3 active, 1 inactive)');
        $this->command->info('   - 4 Channels (3 regular, 1 direct)');
        $this->command->info('   - 10 Messages (text, file, reply, edited)');
    }
}