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
        /*Schema::table('team_user', function (Blueprint $table) {
            // table team_user pivot is users in teams or == table team_members
            $table->string('role')->default('member')->after('accepted');
            // status of the user in the team == accepted
            // leader / admin / member

        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('folder_name')->nullable()->after('notes');
            //  $table->json('attachments')->nullable()->after('notes'); // stocke les chemins des fichiers
            // $table->integer('attachments_count')->default(0)->after('attachments');
            // Compteur pour l'affichage rapide
            // $table->unsignedInteger('attachments_count')->default(0)
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       /* Schema::table('team_user', function (Blueprint $table) {
            //
            $table->dropColumn('role');
        });

         Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('folder_name','attachments');
    });*/
    }
};


