<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('startup_idea_id')->constrained()->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'contacted', 'declined'])->default('pending');
            $table->timestamps();

            $table->unique(['investor_id', 'startup_idea_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_interests');
    }
};