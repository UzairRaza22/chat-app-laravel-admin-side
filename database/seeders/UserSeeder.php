<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('👥 Test Users:');
        $this->command->info('   - John Doe (john@test.com) - Active');
        $this->command->info('   - Jane Smith (jane@test.com) - Active');
        $this->command->info('   - Mike Johnson (mike@test.com) - Active');
        $this->command->info('   - Sarah Wilson (sarah@test.com) - Active');
        $this->command->info('   - Emma Davis (emma@test.com) - Inactive');
    }
}
