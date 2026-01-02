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
        // Points champs == Difficultés et leurs points associés (pour gamification / estimation)
        $difficulties = [
            'easy'   => [1, 2],
            'medium' => [3, 4],
            'hard' => [5],
            'challenging'=> [6],
        ];
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

        // === Création de 4 leaders ===
        $leaders = [];
        for ($i = 1; $i <= 4; $i++) {
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



        // 4 membres
        $members = [];
        for ($i = 1; $i <= 4; $i++) {
            $members[] = User::create([
                'name' => "Membre {$i}",
                'email' => "member{$i}@teamlinks.com",
                'password' => Hash::make("member{$i}pwd"),
                'role' => 'member',
                'position' => 'Developer',
                'birthdate' => '1998-01-01',
            ]);
        }

        $this->command->info("Seeder TeamLinks complet: 4 leaders, 3 équipes par leader, 3 projets par équipe, 4 membres.");

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
        // === Pour chaque leader : 3 équipes, 3 projets par équipe, tâches, posts, etc. ===
        foreach ($leaders as $leader) {
            for ($t = 1; $t <= 3; $t++) {
                // Création de l'équipe
                $team = $leader->teamsAsLeader()->create([
                    'name' => "Team {$t} - {$leader->name}",
                    'description' => "Team {$t} managed by {$leader->name}",
                    'invite_code' => strtoupper(bin2hex(random_bytes(4))),
                ]);

                // Ajouter des membres aléatoires à l'équipe
                // Ajout de 3 à 6 membres aléatoires dans l'équipe
                $selectedMembers = collect($members)->random(rand(2, 4));
                foreach ($selectedMembers as $member) {
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
                        'description' => "Description for project {$p} in {$team->name}",
                        'start_date' => now()->subDays(rand(10, 30)),
                        'end_date' => now()->addDays(rand(30, 120)),
                    ]);

                    // 5 tâches par projet
                    for ($taskNum = 1; $taskNum <= 5; $taskNum++) {
                        $assignedUser = collect([$leader, ...$selectedMembers])->random();

                        $difficultyKey = array_rand($difficulties);
                        $points = $difficulties[$difficultyKey][array_rand($difficulties[$difficultyKey])];
                        // Dates réalistes
                        $startAt = now()->addDays(rand(-10, 5)); // peut être dans le passé ou futur proche
                        $dueDate = (bool) rand(0, 1) ? $startAt->copy()->addDays(rand(3, 30)) : null;

                        $task = Task::create([
                            'project_id' => $project->id,
                            'title' => "Task {$taskNum} - {$project->name}",
                            'description' => "Detailed description for task {$taskNum}",
                            'assigned_to' => $assignedUser->id,
                            'start_at'     => $startAt,
                            'due_date'     => $dueDate,
                            'difficulty'   => $difficultyKey,
                            //'points' => rand(1,6),
                            'points'       => $points,
                            'status' => ['todo','in_progress','completed'][array_rand(['todo','in_progress','completed'])],

                            'priority'     => rand(1, 5), // 1 = urgent, 5 = très basse
                            //'pinned'       => rand(0, 10) === 0, // 10% de chance d'être épinglée
                            //'pinned_at'    => null, // géré automatiquement par observer ou mutator si tu en as un
                            //'reminder_at'  => rand(0, 1) ? $dueDate?->subDays(rand(1, 3)) : null,
                            'notes'        => rand(0, 2) ? 'Note importante : vérifier avec le client avant validation.' : null,
                            //'attachments_count' => rand(0, 4),
                           //'comments_count'    => rand(0, 8),

                        ]);

                        // 2 à 4 sous-tâches

                        for ($s = 1; $s <= rand(2,4); $s++) {
                            Subtask::create([
                                'task_id' => $task->id,
                                'title' => "Subtask {$s} de {$task->title}",
                                'status' => ['pending','in_progress','completed'][array_rand(['pending','in_progress','completed'])],
                                'assigned_to' => rand(0, 1) ? collect([$leader, ...$selectedMembers])->random()->id : null,
                                'priority' => rand(1,5),
                                'due_date' => rand(0,1) ? now()->addDays(rand(1,7)) : null,

                                'order_pos'        => $s * 10, // espacement pour drag & drop futur
                                'points'           => rand(1, 5),
                                'notes'            => rand(0, 3) ? fake()->paragraph(1) : null,
                                //'estimated_hours'  => rand(1, 8),
                                //'actual_hours'     => rand(0, 10),
                                'started_at'       => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                                'completed_at'     => null, // sera rempli par trigger ou observer si status = completed
                            ]);
                        }
                    }

                    // Posts par projet / équipe
                    // 3 posts par équipe (liés à l'équipe, pas au projet directement)
                    $teamUsers = $team->members()->get()->push($leader); // inclut le leader
                    for ($postNum = 1; $postNum <= 3; $postNum++) {

                         $post = Post::create([
                            'team_id' => $team->id,
                            'user_id' => $teamUsers->random()->id,
                            'title' => "Post {$postNum} - {$team->name}",
                            'content' => "This is the content of post {$postNum} in {$team->name}. Discussing progress and ideas.",
                            'excerpt' => Str::limit("This is the content of post {$postNum} in {$team->name}", 50),
                            'visibility' => 'team',
                            'pinned' => rand(0,1),
                            'views_count' => rand(0,100),
                        ]);

                        // Commentaires // 1 à 3 commentaires par post
                        for ($c = 1; $c <= rand(1,3); $c++) {
                            PostComment::create([
                                'post_id' => $post->id,
                                'user_id' => $teamUsers->random()->id,
                                'content' => "Comment {$c} on {$post->title}",
                            ]);
                        }

                        // Likes
                        $likers = $teamUsers->random(rand(1, $teamUsers->count()));
                        foreach ($likers as $user) {
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
                    'from_id' => $allUsers->where('id', '!=', $user->id)->random()->id,
                    'title' => 'Notification TeamLinks:TeamLinks Update',
                    'message' => 'You have a new activity: task assignment, comment, or mention.',
                    'type' => $types[array_rand($types)],
                    //'read_at' => rand(0,1) ? now() : null,
                    // 'read_at' => rand(0, 3) === 0 ? now() : null, // 25% lues
                ]);
            }
        }

        $this->command->info("Seeder complet exécuté avec succès : utilisateurs, équipes, projets, tâches, sous-tâches, posts, commentaires, likes et notifications !");

        $this->command->info('Created: 5 leaders | 15 teams | 45 projects | 225 tasks | ~800 subtasks | posts, comments, likes, notifications.');
    }
}
