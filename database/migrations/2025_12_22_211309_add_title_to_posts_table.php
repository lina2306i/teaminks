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
        Schema::table('posts', function (Blueprint $table) {
            //
            // Titre du post (obligatoire ou non, selon toi)
            $table->string('title')->nullable()->after('id'); // nullable si tu veux permettre des posts sans titre

            // Optionnel : autres colonnes utiles pour un système de posts moderne
            $table->text('excerpt')->nullable()->after('content'); // résumé automatique ou manuel
            $table->enum('visibility', ['team', 'public'])->default('team')->after('team_id'); // futur : posts publics ?
            $table->boolean('pinned')->default(false)->after('title'); // épingler un post en haut
            $table->integer('views_count')->default(0)->after('updated_at'); // nombre de vues (bonus)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
            $table->dropColumn(['title', 'excerpt', 'visibility', 'pinned', 'views_count']);
        });
    }
};
