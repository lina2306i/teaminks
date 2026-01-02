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
        Schema::table('tasks', function (Blueprint $table) {
            //
            // Ajouter les nouveaux champs

            // Modifier due_date pour enlever la contrainte d'unicité si elle existe (pas dans ton cas, mais au cas où)
            // Changer due_date en datetime (si déjà date)
            //$table->dateTime('due_date')->nullable()->change();
            //Important : Pour utiliser ->change(), ajoute ce package si tu ne l’as pas :
            //  >  composer require doctrine/dbal   Puis exécute : php artisan migrate
            // Pas besoin de modifier le type, il reste date nullable

            // start_at en datetime
                // $table->dateTime('start_at')->nullable()->after('description');

            // Subtasks en JSON
                //$table->json('subtasks')->nullable()->after('description'); // pour stocker un tableau de strings
            // Difficulty
                //$table->enum('difficulty', ['easy', 'medium', 'hard','challenging'])->default('medium')->after('due_date');
            // Points
                //$table->integer('points')->default(1)->after('difficulty');


            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //

            //$table->dropColumn(['subtasks', 'start_at', 'difficulty', 'points']);
            //$table->date('due_date')->nullable()->change(); // revert si besoin

        });
    }
};
