<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin, users, workspaces, and channels from other seeders
        $admin = \App\Models\Admin\Admin::where('email', 'admin@test.com')->first();
        $users = \App\Models\Admin\User::all();
        $workspaces = \App\Models\Admin\Workspace::all();
        $channels = \App\Models\Admin\Channel::all();
        
        if (!$admin || $users->count() < 6 || $workspaces->count() < 7 || $channels->count() < 4) {
            $this->command->error('❌ Please run AdminSeeder, UserSeeder, WorkspaceSeeder, TeamSeeder, and ChannelSeeder first!');
            return;
        }

        // Create Messages
        $messages = [];
        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[0]->_id,
            'user_id' => (string)$users[0]->_id,
            'content' => 'Welcome to general channel! Let\'s start collaborating.',
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
            'content' => 'Let\'s discuss new API endpoints here.',
            'message_type' => 'text',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[0]->_id,
            'channel_id' => (string)$channels[1]->_id,
            'user_id' => (string)$users[2]->_id,
            'content' => 'I\'ve uploaded API documentation.',
            'message_type' => 'file',
            'file_name' => 'api-docs.pdf',
            'file_path' => '/uploads/api-docs.pdf',
            'file_mime' => 'application/pdf',
        ]);

        $messages[] = Message::create([
            'workspace_id' => (string)$workspaces[1]->_id,
            'channel_id' => (string)$channels[2]->_id,
            'user_id' => (string)$users[1]->_id,
            'content' => 'What do you think about new marketing campaign?',
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
            'content' => 'Hey, can we discuss project timeline privately?',
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
        $messages[] = Message::create([
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
            'content' => 'Here is project screenshot.',
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
            'content' => 'Thanks for feedback on development process.',
            'message_type' => 'text',
        ]);

        $this->command->info('✅ Messages seeded successfully!');
        $this->command->info('💬 Messages:');
        $this->command->info('   - 15 Messages (text, file, reply, edited, direct)');
        $this->command->info('   - Text messages, file uploads, replies, edited messages, direct messages');
    }
}
