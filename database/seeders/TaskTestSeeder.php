<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Subtask;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TaskTestSeeder extends Seeder
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
            //'challenging' => 5,


       // Statuts conformes à tes tables

        $taskStatuses = ['todo', 'in_progress', 'completed'];
        $subtaskStatuses = ['pending', 'in_progress', 'completed'];

        // Récupère tous les utilisateurs qui peuvent être assignés (leaders + members)
        $assignableUsers = User::whereIn('role', ['leader', 'member'])->get();
        // Si aucun utilisateur, on arrête pour éviter les erreurs
        if ($assignableUsers->isEmpty()) {
            $this->command->warn('Aucun utilisateur leader ou member trouvé pour assigner les tâches.');
            return;
        }

        // Pour chaque projet existant
        Project::all()->each(function ($project) use (
            $difficulties, $taskStatuses, $subtaskStatuses,$assignableUsers ,
        ) {
            // Nombre aléatoire de tâches par projet (entre 4 et 9)
            $taskCount = rand(4, 8);

             //$users = User::whereIn('role', ['leader', 'member'])->get();

            for ($i = 1; $i <= $taskCount; $i++) {

                $difficultyKey = array_rand($difficulties);
                $points = $difficulties[$difficultyKey][array_rand($difficulties[$difficultyKey])];

                // Dates réalistes
                $startAt = now()->addDays(rand(-10, 5)); // peut être dans le passé ou futur proche
                $dueDate = (bool) rand(0, 1) ? $startAt->copy()->addDays(rand(3, 30)) : null;


                  //'start_at' => now(),
                 // 'due_date' => now()->addDays(rand(3, 14)),


                $task = Task::create([
                    'project_id'   => $project->id,
                    'title'        => "Task {$i} — " . Str::limit($project->name, 30),
                    'description'  => "Description détaillée de la tâche {$i} pour le projet {$project->name}. Objectif : avancer sur une fonctionnalité clé.",
                    'assigned_to'  => $assignableUsers->random()->id,
                    'start_at'     => $startAt,
                    'due_date'     => $dueDate,
                    'difficulty'   => $difficultyKey,
                    'points'       => $points,
                    'status'       => $taskStatuses[array_rand($taskStatuses)],
                    'priority'     => rand(1, 5), // 1 = urgent, 5 = très basse
                     //'pinned'       => rand(0, 10) === 0, // 10% de chance d'être épinglée
                     //'pinned_at'    => null, // géré automatiquement par observer ou mutator si tu en as un
                    //'reminder_at'  => rand(0, 1) ? $dueDate?->subDays(rand(1, 3)) : null,
                    'notes'        => rand(0, 2) ? 'Note importante : vérifier avec le client avant validation.' : null,
                    //'attachments_count' => rand(0, 4),
                    //'comments_count'    => rand(0, 8),
                ]);

                // Subtasks
                // Créer 2 à 6 subtasks par tâche
                $subtaskCount = rand(2,5);

                for ($j = 1; $j <= $subtaskCount; $j++) {
                    $subtaskDueDate = rand(0, 1) ?  now()->addDays(rand(1, 15)) : null;

                    Subtask::create([
                        'task_id'          => $task->id,
                        'title'            => "Subtask {$j} : " . fake()->sentence(4),
                        'status'           => $subtaskStatuses[array_rand($subtaskStatuses)],
                        'order_pos'        => $j * 10, // espacement pour drag & drop futur
                        'assigned_to'      => rand(0, 3) > 0 ? $assignableUsers->random()->id : null, // ~75% assignée
                        'due_date'         => $subtaskDueDate,
                        'priority'         => rand(1, 5),
                        'points'           => rand(1, 5),
                        'notes'            => rand(0, 3) ? fake()->paragraph(1) : null,
                      //'estimated_hours'  => rand(1, 8),
                      //'actual_hours'     => rand(0, 10),
                        'started_at'       => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                        'completed_at'     => null, // sera rempli par trigger ou observer si status = completed
                    ]);
                }

            }
        });

        $this->command->info("Tasks & Subtasks seeded successfully");
    }
}
