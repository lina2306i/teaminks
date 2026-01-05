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
          //  $table->string('folder_name')->nullable()->after('notes');
          //  $table->json('attachments')->nullable()->after('notes'); // stocke les chemins des fichiers
        // $table->integer('attachments_count')->default(0)->after('attachments');
         // Compteur pour l'affichage rapide
           // $table->unsignedInteger('attachments_count')->default(0)
        });

    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // $table->dropColumn('folder_name','attachments');
    });
    }
};
