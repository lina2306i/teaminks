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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade'); // == user_id | le leader/propriétaire
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');  // pas exister
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->datetime('due_date')->nullable();  //== deadline | date limite du projet

            // On garde due_date pour compatibilité ou on peut le supprimer plus tard
            // $table->dropColumn('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
