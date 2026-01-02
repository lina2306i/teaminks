<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'completed'])->default('todo');

            // start_at en datetime
            $table->dateTime('start_at')->nullable();
            $table->dateTime('due_date')->nullable();

            // Difficulty :: À quel point la tâche est techniquement complexe ou demande d’effort
            $table->enum('difficulty', ['easy', 'medium', 'hard','challenging'])->default('medium');
            // Points 1..6 .: Valeur en points (souvent pour gamification ou estimation agile — Story Points)
            $table->integer('points')->default(5);
            //Priority :: À quel point la tâche est importante / urgente pour l’équipe ou le leader
            $table->tinyInteger('priority')
                  ->default(3)
                    //->enum('priority', ['low', 'normal', 'high', 'urgent']) ->default('normal')
                  ->comment('1=Urgent, 2=High, 3=Normal, 4=Low, 5=Very Low');

            $table->boolean('pinned')->default(false);
            $table->timestamp('pinned_at')->nullable();

            // Ajouter les nouveaux champs
            // Modifier due_date pour enlever la contrainte d'unicité si elle existe (pas dans ton cas, mais au cas où)
            // Changer due_date en datetime (si déjà date)
            //$table->dateTime('due_date')->nullable()->change();
            //Important : Pour utiliser ->change(), ajoute ce package si tu ne l’as pas :
            //  >  composer require doctrine/dbal   Puis exécute : php artisan migrate
            // Pas besoin de modifier le type, il reste date nullable


            $table->dateTime('reminder_at')->nullable();
            $table->text('notes')->nullable(); // Notes ou commentaires libres
            // Subtasks en JSON
            // $table->json('subtasks')->nullable()->after('description'); // pour stocker un tableau de strings //removed
            $table->integer('attachments_count')->default(0); // Compteur pour l'affichage rapide
            $table->integer('comments_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
