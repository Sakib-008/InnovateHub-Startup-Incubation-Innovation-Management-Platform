<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['founder', 'mentor', 'investor', 'admin'])
                  ->default('founder')
                  ->after('email');
            //   An ENUM restricts a column to a predefined list of values.
            // Places the role column immediately after the email column in the table.
            $table->string('phone')->nullable()->after('role');
            $table->text('bio')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('linkedin')->nullable()->after('avatar');
            $table->string('expertise')->nullable()->after('linkedin'); // mentor-specific
            $table->boolean('is_active')->default(true)->after('expertise');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'bio', 'avatar', 'linkedin', 'expertise', 'is_active']);
        });
    }
};