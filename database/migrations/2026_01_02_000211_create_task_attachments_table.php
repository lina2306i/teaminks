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
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->string('filename');                    // nom original
            $table->string('path');                        // chemin stocké (storage/app/public/tasks/...)
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size');            // en bytes
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('folder_name')->nullable()->after('notes');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('task_attachments');
        Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('folder_name');
    });
    }
};
