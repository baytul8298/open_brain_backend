<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds application-level columns to auth.users (resolved via search_path).
 * The actual table is the SQL-defined auth.users with UUID PK and password_hash.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->nullable()->unique();
            $table->string('user_type', 50)->nullable();
            $table->string('salt')->nullable();
            $table->string('language', 10)->default('en');
            $table->string('remember_token', 100)->nullable();
            $table->timestampTz('email_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'user_type', 'salt', 'language', 'remember_token', 'email_verified_at']);
        });
    }
};
