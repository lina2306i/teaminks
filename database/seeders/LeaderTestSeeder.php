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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeaderTestSeeder extends Seeder
{
    public function run(): void
    {
        // === Supprimer toutes les données existantes ===
       // User::truncate();


        // === Création de l'admin ===
        $admin = User::firstOrCreate([
            'email' => 'lina.admin@teamlinks.com',
        ], [
            'name' => 'Lina Admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'position' => 'Administrator',
            'birthdate' => '1997-06-23',
        ]);

        // === Création de 5 leaders ===
        $leaders = [];
        for ($i = 8; $i <= 12; $i++) {
            $leaders[] = User::firstOrCreate([
                'email' => "leader{$i}@teamlinks.com"
            ], [
                'name' => "Leader {$i}",
                'password' => Hash::make("leader{$i}pwd"),
                'role' => 'leader',
                'position' => "Lead Developer {$i}",
                'birthdate' => '1995-05-15',
            ]);
        }



        // 6 membres
        $members = [];
        for ($i = 1; $i <= 6; $i++) {
            $members[] = User::create([
                'name' => "Membre {$i}",
                'email' => "member{$i}@teamlinks.com",
                'password' => Hash::make("member{$i}pwd"),
                'role' => 'member',
                'position' => 'Developer',
                'birthdate' => '1998-01-01',
            ]);
        }

        $this->command->info("Seeder TeamLinks complet: 5 leaders, 3 équipes par leader, 3 projets par équipe, 6 membres.");

                // === Pour chaque leader, créer 3 équipes et 3 projets par équipe ===
       /* foreach ($leaders as $leader) {
            for ($teamIndex = 1; $teamIndex <= 3; $teamIndex++) {
                $team = $leader->teamsAsLeader()->create([
                    'name' => "Équipe {$teamIndex} de {$leader->name}",
                    'description' => "Équipe {$teamIndex} dirigée par {$leader->name}",
                    'invite_code' => strtoupper(bin2hex(random_bytes(4))),
                ]);

                for ($projectIndex = 1; $projectIndex <= 3; $projectIndex++) {
                    $leader->projects()->create([
                        'name' => "Projet {$projectIndex} - {$team->name}",
                        'description' => "Projet {$projectIndex} pour {$team->name}",
                        'team_id' => $team->id,
                        'due_date' => now()->addWeeks($projectIndex * 2)
                    ]);
                }
            }
        }*/

        // === Création des équipes et projets ===
        foreach ($leaders as $leader) {
            for ($t = 1; $t <= 3; $t++) {
                $team = $leader->teamsAsLeader()->create([
                    'name' => "Équipe {$t} - {$leader->name}",
                    'description' => "Description de l'équipe {$t} de {$leader->name}",
                    'invite_code' => strtoupper(bin2hex(random_bytes(4))),
                ]);

                // Ajouter des membres aléatoires à l'équipe
                $teamMembers = collect($members)->random(rand(3,6));
                foreach ($teamMembers as $member) {
                    $team->members()->attach($member->id, [
                        'status' => 'accepted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // 3 projets par équipe
                for ($p = 1; $p <= 3; $p++) {
                    $project = $leader->projects()->create([
                        'team_id' => $team->id,
                        'name' => "Projet {$p} - {$team->name}",
                        'description' => "Description du projet {$p} pour {$team->name}",
                        //'start_date' => now()->subDays(rand(0,30)),
                        //'end_date' => now()->addDays(rand(30,90)),
                    ]);

                    // 5 tâches par projet
                    for ($taskNum = 1; $taskNum <= 5; $taskNum++) {
                        $task = Task::create([
                            'project_id' => $project->id,
                            'title' => "Task {$taskNum} - {$project->name}",
                            'description' => "Description de la tâche {$taskNum}",
                            'assigned_to' => collect(array_merge([$leader], $members))->random()->id,
                            'start_at' => now(),
                            'due_date' => now()->addDays(rand(3,14)),
                            'difficulty' => ['easy','medium','hard'][array_rand(['easy','medium','hard'])],
                            'points' => rand(1,5),
                            'status' => ['todo','in_progress','completed'][array_rand(['todo','in_progress','completed'])],
                        ]);

                        // 2 à 4 sous-tâches
                        for ($s = 1; $s <= rand(2,4); $s++) {
                            Subtask::create([
                                'task_id' => $task->id,
                                'title' => "Subtask {$s} de {$task->title}",
                                'status' => ['pending','in_progress','completed'][array_rand(['pending','in_progress','completed'])],
                                'assigned_to' => rand(0,1) ? collect(array_merge([$leader], $members))->random()->id : null,
                                'priority' => rand(1,5),
                                'due_date' => rand(0,1) ? now()->addDays(rand(1,7)) : null,
                            ]);
                        }
                    }

                    // Posts par projet / équipe
                    $teamUsers = $team->members()->get();
                    for ($postNum = 1; $postNum <= 3; $postNum++) {
                        $post = Post::create([
                            'team_id' => $team->id,
                            'user_id' => $teamUsers->random()->id,
                            'title' => "Post {$postNum} - {$team->name}",
                            'content' => "Contenu du post {$postNum} pour {$team->name}",
                            'excerpt' => Str::limit("Contenu du post {$postNum}",50),
                            'visibility' => 'team',
                            'pinned' => rand(0,1),
                            'views_count' => rand(0,100),
                        ]);

                        // Commentaires
                        for ($c = 1; $c <= rand(1,3); $c++) {
                            PostComment::create([
                                'post_id' => $post->id,
                                'user_id' => $teamUsers->random()->id,
                                'content' => "Commentaire {$c} sur {$post->title}",
                            ]);
                        }

                        // Likes
                        foreach ($teamUsers->random(rand(1,$teamUsers->count())) as $user) {
                            PostLike::create([
                                'post_id' => $post->id,
                                'user_id' => $user->id,
                            ]);
                        }
                    }
                }
            }
        }

        // === Notifications pour tous les utilisateurs ===
        $allUsers = User::all();
        $types = ['success','info','warning','error'];
        foreach ($allUsers as $user) {
            for ($n=1; $n<=rand(3,6); $n++) {
                Notification::create([
                    'user_id' => $user->id,
                    'from_id' => $allUsers->random()->id,
                    'title' => 'Notification TeamLinks',
                    'message' => "Vous avez une nouvelle action à faire sur TeamLinks.",
                    'type' => $types[array_rand($types)],
                    //'read_at' => rand(0,1) ? now() : null,
                ]);
            }
        }

        $this->command->info("Seeder complet exécuté avec succès : utilisateurs, équipes, projets, tâches, sous-tâches, posts, commentaires, likes et notifications !");


        $this->command->info("Seeder TeamLinks complet: 5 leaders, 3 équipes par leader, 3 projets par équipe, 6 membres.");

    }
}
