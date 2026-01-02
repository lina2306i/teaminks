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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'title')->nullable();// nullable si tu veux permettre des posts sans titre
            $table->boolean('pinned')->default(false); // épingler un post en haut
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('visibility', ['team', 'public'])->default('team'); // futur : posts publics ?
            $table->text('content');
            $table->integer('views_count')->default(0) ; // nombre de vues (bonus)
            $table->text('excerpt')->nullable();    // résumé automatique ou manuel
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
