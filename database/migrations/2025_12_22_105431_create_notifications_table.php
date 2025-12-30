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
            // Destinataire (l'utilisateur qui reçoit la notif)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('notifiable_id')->nullable(); // ex: task_id, post_id, etc.
            $table->string('notifiable_type')->nullable(); // polymorphic
            // Champs principaux
            $table->string('title'); // titre court
            $table->text('message'); // corps de la notification
            // Statut lu / non lu
            $table->boolean('read')->default(false);
            // Timestamps + soft deletes si tu veux (optionnel mais utile)
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
