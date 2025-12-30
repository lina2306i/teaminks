<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Subtask;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskTestSeeder extends Seeder
{
    public function run(): void
    {
        $difficulties = [
            'easy'   => [1, 2],
            'medium' => [3, 4],
            'hard' => [5],
        ];
            //'challenging' => 5,


       // Statuts conformes à tes tables

        $taskStatuses = ['todo', 'in_progress', 'completed'];
        $subtaskStatuses = ['pending', 'in_progress', 'completed'];

        Project::all()->each(function ($project) use ($difficulties, $taskStatuses, $subtaskStatuses) {

            $users = User::whereIn('role', ['leader', 'member'])->get();

            for ($i = 1; $i <= rand(5, 10); $i++) {

                $difficultyKey = array_rand($difficulties);

                $task = Task::create([
                    'project_id' => $project->id,
                    'title' => "Task {$i} - {$project->name}",
                    'description' => "Description de la task {$i}",
                    'assigned_to' => $users->random()->id,
                    'start_at' => now(),
                    'due_date' => now()->addDays(rand(3, 14)),
                    'difficulty' => $difficultyKey,
                    'points' => $difficulties[$difficultyKey][array_rand($difficulties[$difficultyKey])],
                    'status' => $taskStatuses[array_rand($taskStatuses)],
                ]);

                // Subtasks
                for ($j = 1; $j <= rand(2, 5); $j++) {
                    Subtask::create([
                        'task_id' => $task->id,
                        'title' => "Subtask {$j} de {$task->title}",
                        'status' => $subtaskStatuses[array_rand($subtaskStatuses)],
                        'assigned_to' => rand(0, 1) ? $users->random()->id : null,
                        'priority' => rand(1, 5),
                        'due_date' => rand(0, 1) ? now()->addDays(rand(1, 7)) : null,
                    ]);
                }
            }
        });

        $this->command->info("Tasks & Subtasks seeded successfully");
    }
}
