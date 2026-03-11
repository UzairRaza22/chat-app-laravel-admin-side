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

        // Create Users with more comprehensive data
        $users = [];
        $users[] = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

        $users[] = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

        $users[] = User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

        $users[] = User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

        $users[] = User::create([
            'name' => 'Alex Brown',
            'email' => 'alex@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

        $users[] = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma@test.com',
            'password' => Hash::make('password123'),
            'is_active' => false, // Inactive user for testing
            'workspace_ids' => [],
            'team_ids' => [],
        ]);

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

        // Create Messages
        $messages = [];
        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[0]->_id,
            'content' => 'Welcome to the general channel! Let\'s start collaborating.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'Thanks for setting this up! Looking forward to working together.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[1]->_id,
            'user_id' => (string)$users[0]->_id,
            'content' => 'Let\'s discuss the new API endpoints here.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[1]->_id,
            'user_id' => (string)$users[2]->_id,
            'content' => 'I\'ve uploaded the API documentation.',
            'message_type' => 'file',
            'file_name' => 'api-docs.pdf',
            'file_path' => '/uploads/api-docs.pdf',
            'file_mime' => 'application/pdf',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[1]->_id,
            'channel_id' => (string)$channels[2]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'What do you think about the new marketing campaign?',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[1]->_id,
            'channel_id' => (string)$channels[2]->_id,
            'user_id' => (string)$users[3]->_id,
            'content' => 'I think it needs more focus on social media.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[3]->_id,
            'user_id' => (string)$users[0]->_id,
            'content' => 'Hey, can we discuss the project timeline privately?',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[3]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'Sure! I think we can finish it by next week.',
            'message_type' => 'text',
        ]);

        // Reply message
        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[2]->_id,
            'content' => 'Great initiative! Count me in.',
            'message_type' => 'text',
            'parent_message_id' => (string)$messages[0]->_id, // Reply to first message
        ]);

        // Edited message
        $editedMessage = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'This message has been edited for clarity.',
            'message_type' => 'text',
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        // Add more messages for better testing
        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[2]->_id,
            'channel_id' => null, // Direct message
            'user_id' => (string)$users[0]->_id,
            'content' => 'Direct message to discuss project details.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[2]->_id,
            'content' => 'Here is the project screenshot.',
            'message_type' => 'file',
            'file_name' => 'project-screenshot.png',
            'file_path' => '/uploads/project-screenshot.png',
            'file_mime' => 'image/png',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[1]->_id,
            'channel_id' => (string)$channels[2]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'Let\'s schedule a meeting for next week.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[1]->_id,
            'user_id' => (string)$users[0]->_id,
            'content' => 'Code review completed. Everything looks good!',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[2]->_id,
            'channel_id' => null, // Another direct message
            'user_id' => (string)$users[2]->_id,
            'content' => 'Thanks for the feedback on the development process.',
            'message_type' => 'text',
        ]);

        $this->command->info('✅ Admin test data seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 1 Admin');
        $this->command->info('   - 4 Users (3 active, 1 inactive)');
        $this->command->info('   - 3 Workspaces');
        $this->command->info('   - 4 Teams (3 active, 1 inactive)');
        $this->command->info('   - 4 Channels (3 regular, 1 direct)');
        $this->command->info('   - 15 Messages (text, file, reply, edited, direct)');
        $this->command->info('');
        $this->command->info('🔑 Admin Login:');
        $this->command->info('   Email: admin@test.com');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('👥 Test Users:');
        $this->command->info('   - John Doe (john@test.com) - Active');
        $this->command->info('   - Jane Smith (jane@test.com) - Active');
        $this->command->info('   - Mike Johnson (mike@test.com) - Active');
        $this->command->info('   - Sarah Wilson (sarah@test.com) - Inactive');
        $this->command->info('');
        $this->command->info('🏢 Workspaces:');
        $this->command->info('   - Tech Company Workspace');
        $this->command->info('   - Marketing Team Workspace');
        $this->command->info('   - Development Workspace');
        $this->command->info('');
        $this->command->info('👥 Teams:');
        $this->command->info('   - Frontend Team (Active)');
        $this->command->info('   - Backend Team (Active)');
        $this->command->info('   - Marketing Team (Active)');
        $this->command->info('   - DevOps Team (Inactive)');
        $this->command->info('');
        $this->command->info('📢 Channels:');
        $this->command->info('   - general (public)');
        $this->command->info('   - development (private)');
        $this->command->info('   - marketing-ideas (public)');
        $this->command->info('   - direct-messages (direct)');
        $this->command->info('');
        $this->command->info('💡 Ready to test your Admin API with Postman!');
    }
}