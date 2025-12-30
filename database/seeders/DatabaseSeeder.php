<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Désactiver temporairement les clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider les tables concernées si nécessaire
        // Vider les tables
        User::truncate();
        Team::truncate();
        Project::truncate();
        Task::truncate();
        Subtask::truncate();
        Post::truncate();
        PostComment::truncate();
        PostLike::truncate();
        Notification::truncate();
        DB::table('team_user')->truncate();


        // Réactiver les clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('pwd1234'),
            'position' => 'Developer',
            'birthdate' => '1995-05-15'
        ]);

        User::factory()->create([
            'name' => 'Leader Test',
            'email' => 'leader@test.com',
            'password' => Hash::make('pwd123'),
            'role' => 'leader',
            'position' => 'Full Stack Developer',
            'birthdate' => '1995-05-15'
        ]);

        User::factory()->create([
            'name' => 'Lina Admin',
            'email' => 'lina.hkl2306@gmail.com',
            'password' => Hash::make('pwd123'),
            'role' => 'admin',
            'position' => 'admin Developer',
            'birthdate' => '1997-06-23',
        ]);

        // database/seeders/DatabaseSeeder.php
        User::factory()->create([
            'name' => 'Leader Test',
            'email' => 'leadertest@test.com',
            'role' => 'leader',
            'password' => bcrypt('password'),
            'position' => 'chef project',
            'birthdate' => '1995-05-15',
        ]);

        $leader = User::where('role', 'leader')->first();
        $team = $leader->teamsAsLeader()->create([
            'name' => 'Équipe Alpha',
            'description' => 'Première équipe',
            'invite_code' => strtoupper(bin2hex(random_bytes(4))), // 4 octets → 8 caractères hex
        ]);

        $leader->projects()->create(['name' => 'Site Web TeamLink', 'description' => 'Refonte complète',]);




        $leader2 = User::create([
            'name' => 'Moataz Leader',
            'email' => 'leader@teamlink.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
            'position' => 'backend Developer',
            'birthdate' => '1995-05-15'
        ]);

        // Créer une équipe
        $team2 = $leader2->teamsAsLeader()->create([
            'name' => 'Équipe Développement',
            'description' => 'Équipe principale du projet TeamLink',
            'invite_code' => strtoupper(bin2hex(random_bytes(4))) ,// génère un code aléatoire
        ]);

        // Ajouter des projets
        $leader2->projects()->createMany([
            ['name' => 'Refonte Dashboard', 'description' => 'Nouveau design moderne', 'due_date' => '2026-02-01', 'team_id' => $team->id],
            ['name' => 'API Mobile', 'description' => 'API REST pour app mobile', 'due_date' => '2026-03-15', 'team_id' => $team->id],
        ]);

        echo "Leader créé : leader@teamlink.com / password\n";



        $l= User::factory()->create([
            'name' => 'ihKaled',
            'email' => 'ihkhaled@team.com',
            'password' => Hash::make('pwd123ihk'),
            'role' => 'leader',
            'position' => 'Frontend Enginer-Developer',
            'birthdate' => '1999-08-24'
        ]);

        $l->projects()->create(['name' => 'Site Web TeamLink', 'description' => 'Pfa Project']);
        $l->projects()->create(['name' => 'Application-i TeamLink', 'description' => 'Team project I']);
        $tea = $l->teamsAsLeader()->createMany([
            [
                'name' => 'Équipe Beta',
                'description' => 'my first  équipe' ,
                'invite_code' => strtoupper(bin2hex(random_bytes(4))) ,// génère un code aléatoire
            ],
            [
                'name' => ' Team test',
                'description' => 'Équipe test  du projet TeamLink',
                'invite_code' => strtoupper(bin2hex(random_bytes(4))) ,// génère un code aléatoire
            ],
        ]);


         User::factory()->create([
            'name' => 'LunaM',
            'email' => 'lina@teamlinks.com',
            'password' => Hash::make('pwd123lun'),
            'role' => 'member',
            'position' => 'Full Stack Developer',
            'birthdate' => '1995-05-16'
        ]);
        User::factory()->create([
            'name' => 'userMb',
            'email' => 'member@teamliks.com',
            'password' => Hash::make('pwd123mb'),
            'role' => 'member',
            'position' => 'stagaire',
            'birthdate' => '1995-05-17'
        ]);




        $this->call(class: LeaderTestSeeder::class);
        $this->call(class: TaskTestSeeder::class);
        $this->call(class:  NotificationTestSeeder::class);
       // $this->call(class: TaskTestSeeder::class);

    }
}
