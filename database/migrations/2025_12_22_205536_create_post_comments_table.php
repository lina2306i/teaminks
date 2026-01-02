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
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //Pour les réponses nested (replies) :: Indispensable si tu veux un système de commentaires imbriqués (très courant).
            $table->foreignId('parent_id')->nullable()->constrained('post_comments')->onDelete('cascade');
            $table->text('content');
            // Modération (optionnel mais très utile) :: Pour une modération : les commentaires ne s'affichent pas tant qu'un admin ne les approuve pas (anti-spam).
            $table->boolean('is_approved')->default(false);


            $table->timestamps();


            // Index pour performances
            $table->index(['post_id', 'created_at']);
            $table->index('parent_id'); // Très utile pour fetch les replies
            $table->index('is_approved'); // Si tu filtres souvent les approuvés
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
