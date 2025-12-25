<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LeaderTestSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un leader
        $leader = User::create([
            'name' => 'Moataz Leader',
            'email' => 'leader@teamlink.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
            'position' => 'Full Stack Developer',
            'birthdate' => '1995-05-15'
        ]);

        // Créer une équipe
        $team = $leader->teamsAsLeader()->create([
            'name' => 'Équipe Développement',
            'description' => 'Équipe principale du projet TeamLink'
        ]);

        // Ajouter des projets
        $leader->projects()->createMany([
            ['name' => 'Refonte Dashboard', 'description' => 'Nouveau design moderne', 'due_date' => '2026-02-01', 'team_id' => $team->id],
            ['name' => 'API Mobile', 'description' => 'API REST pour app mobile', 'due_date' => '2026-03-15', 'team_id' => $team->id],
        ]);

        echo "Leader créé : leader@teamlink.com / password\n";
    }
}
