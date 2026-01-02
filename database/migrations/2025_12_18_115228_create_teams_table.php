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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            // Leader de l'équipe
            $table->foreignId('leader_id')
                  ->constrained('users')
                  ->nullable()
                  ->cascadeOnDelete();

             $table->string('invite_code', 10)->unique();

            //$table->string(column: 'invite_code')->unique();  //ex : AB3D-9F2G ou TEAM1234.
            $table->timestamps();

       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
