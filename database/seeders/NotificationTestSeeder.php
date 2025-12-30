<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationTestSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['success', 'info', 'warning', 'error'];

        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= rand(3, 6); $i++) {
                Notification::create([
                    'user_id' => $user->id,
                    'from_id' => $users->random()->id,
                    'title' => 'Notification système',
                    'message' => 'Une nouvelle action a été effectuée sur TeamLinks',
                    'type' => $types[array_rand($types)],
                    //'read_at' => rand(0, 1) ? now() : null,
                ]);
            }
        }

        $this->command->info("Notifications seeded successfully");
    }
}
