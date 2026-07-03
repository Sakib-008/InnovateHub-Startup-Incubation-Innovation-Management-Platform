<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentorship_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_idea_id')->constrained()->onDelete('cascade');
            $table->foreignId('founder_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('message')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // One request per idea-mentor pair
            $table->unique(['startup_idea_id', 'mentor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentorship_requests');
    }
};