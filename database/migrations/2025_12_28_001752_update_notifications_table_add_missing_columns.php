<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter from_id (expéditeur manuel, nullable)
            // Expéditeur optionnel (celui qui a envoyé la notif manuellement)
            $table->foreignId('from_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('users')
                  ->onDelete('set null');

            // deja exisste dans creation 1  Ajouter polymorphisme (lien vers Task, Project, etc.)
            //$table->unsignedBigInteger('notifiable_id')->nullable()->after('from_id');
            //$table->string('notifiable_type')->nullable()->after('notifiable_id');

            //ou bien

            // MorphTo : permet d'attacher à n'importe quel modèle (Task, Comment, etc.)
            //$table->morphs('notifiable');


            // Optionnel mais utile : type de notification (warning, info, etc.)
            $table->string('type')->default('info')->after('message');
            // Données supplémentaires (payload)
            $table->json('data')->nullable();


            // $table->softDeletes();                      // décommente si tu veux pouvoir "supprimer" sans vraiment effacer// crée notifiable_id + notifiable_type


            // Ajouter index pour polymorphisme (meilleures perfs)
            // Index pour performances
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('notifiable_id');
            $table->index(['user_id', 'read']);

        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['from_id']);
            //$table->dropColumn(['from_id', 'notifiable_id', 'notifiable_type', 'type']);
             $table->dropColumn([
                'from_id',
                'type',
                'data',
            ]);

            $table->dropIndex(['user_id', 'read']);
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
        });
    }
};
