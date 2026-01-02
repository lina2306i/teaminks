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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Ajouter from_id (expéditeur manuel, nullable)
            // Expéditeur optionnel (celui qui a envoyé la notif manuellement)
            $table->foreignId('from_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Destinataire (l'utilisateur qui reçoit la notif)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

             // MorphTo : permet d'attacher à n'importe quel modèle (Task, Comment, etc.)
            //$table->morphs('notifiable'); // crée notifiable_id (unsignedBigInteger) + notifiable_type
            // ou bien like that :
            $table->foreignId('notifiable_id')->nullable(); // ex: task_id, post_id, etc.
            $table->string('notifiable_type')->nullable(); // polymorphic

            // Champs principaux plus simples et performants que title + message séparés)
            $table->string('title'); // titre court
            $table->text('message'); // corps de la notification

            // Optionnel mais utile : type de notification (warning, info, etc.)
            $table->string('type')->default('info');
            // Statut lu / non lu
            $table->boolean('read')->default(false);
            // Timestamps + soft deletes si tu veux (optionnel mais utile)
                        // Ajouter from_id (expéditeur manuel, nullable)


              // deja exisste dans creation 1  Ajouter polymorphisme (lien vers Task, Project, etc.)
            //$table->unsignedBigInteger('notifiable_id')->nullable()->after('from_id');
            //$table->string('notifiable_type')->nullable()->after('notifiable_id');

            //ou bien




            // Données supplémentaires (payload)
            $table->json('data')->nullable();


            // $table->softDeletes();                      // décommente si tu veux pouvoir "supprimer" sans vraiment effacer// crée notifiable_id + notifiable_type



            // Ajouter index pour polymorphisme (meilleures perfs)
            // Index pour performances
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('notifiable_id');
            $table->index(['user_id', 'read']);
            $table->index(['user_id', 'read', 'created_at']); // Pour récupérer les unread récentes
            $table->index('read');
            $table->index('created_at');

            //Group ID (très rare – pour regrouper des notifs similaires) :: Ex: plusieurs commentaires sur la même tâche → une seule notif groupée.
            $table->foreignId('group_id')->nullable()->index();

            //Priority (si tu veux trier les notifs urgentes en haut) ::  Mais encore une fois, tu peux mettre ça dans data['priority'].
            $table->unsignedTinyInteger('priority')->default(3); // 1 = haute, 5 = basse
            $table->index('priority');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
