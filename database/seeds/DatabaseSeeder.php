<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TypeTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(PublicMessagesTableSeeder::class);
        $this->call(ApplyTableSeeder::class);
        $this->call(ChatTableSeeder::class);
        $this->call(DirectMessageTableSeeder::class);
        $this->call(NotificationTableSeeder::class);

    }
}
