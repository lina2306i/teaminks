<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');

            $table->enum('status',[
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            //$table->boolean('completed')->default(false);

            // Ordre d’affichage
            $table->integer('order_pos')->default(0);

            // Assignation
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Dates
           $table->dateTime('due_date')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Priorité (1 = haute, 5 = basse)
            $table->tinyInteger('priority')->default(3);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
