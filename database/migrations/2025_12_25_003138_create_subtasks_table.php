<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');

            $table->enum('status',[
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            //$table->boolean('completed')->default(false);

            // Ordre d’affichage
            $table->integer('order_pos')->default(0);

            // Assignation
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Dates
            $table->timestamp('started_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Points 1..5  :: Valeur en points (souvent pour gamification ou estimation agile — Story Points)
            $table->integer(column: 'points')->default(5);
            // Priorité (1 = haute, 5 = basse)
           // $table->tinyInteger('priority')->default(3);
            //Priority :: À quel point la tâche est importante / urgente pour l’équipe ou le leader
            $table->tinyInteger('priority')
                  ->default(3)
                    //->enum('priority', ['low', 'normal', 'high', 'urgent']) ->default('normal')
                  ->comment('1=Urgent, 2=High, 3=Normal, 4=Low, 5=Very Low');

            $table->text('notes')->nullable(); // Notes ou commentaires libres
            $table->unsignedInteger('estimated_hours')->nullable()->default(0); // Estimation temps
            $table->unsignedInteger('actual_hours')->nullable()->default(0); // Temps réel passé

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
