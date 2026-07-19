<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('founder_id')->constrained('users')->onDelete('cascade');
            // constrained('users') links founder_id to the id column of the users table.
            // onDelete('cascade') means if a founder's account is deleted, all startup ideas submitted by that founder are automatically deleted.
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('pitch_file')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_ideas');
    }
};