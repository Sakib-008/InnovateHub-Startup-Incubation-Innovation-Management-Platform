<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_showcases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_idea_id')->constrained()->onDelete('cascade');
            $table->text('achievements')->nullable();
            $table->text('tagline')->nullable();
            $table->string('website')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_showcases');
    }
};