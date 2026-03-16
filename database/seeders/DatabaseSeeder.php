<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->info('');

        // Run seeders in dependency order
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            WorkspaceSeeder::class,
            TeamSeeder::class,
            ChannelSeeder::class,
            MessageSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('💡 Ready to test your Admin API with Postman!');
        $this->command->info('');
        $this->command->info('📊 Final Summary:');
        $this->command->info('   - 1 Admin');
        $this->command->info('   - 6 Users (5 active, 1 inactive)');
        $this->command->info('   - 7 Workspaces');
        $this->command->info('   - 12 Teams (11 active, 1 inactive)');
        $this->command->info('   - 4 Channels (3 regular, 1 direct)');
        $this->command->info('   - 15 Messages (text, file, reply, edited, direct)');
    }
}
