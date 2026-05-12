<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('admin_id')->constrained('users');
            $table->foreignUuid('assignee_id')->nullable()->constrained('users');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->string('priority', 20)->default('normal');
            $table->enum('status', ['pending', 'in_progress', 'awaiting_review', 'revision', 'ready_for_admin', 'completed'])->default('pending');
            $table->integer('version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
