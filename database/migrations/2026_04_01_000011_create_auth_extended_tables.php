<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Documents auth schema tables created directly via SQL (registered as fake).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth.sessions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id')->index();
            $table->text('refresh_token')->unique();
            $table->jsonb('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('auth.password_reset_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->text('token_hash')->unique();
            $table->boolean('used')->default(false);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('auth.email_verification_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->text('token_hash')->unique();
            $table->boolean('used')->default(false);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('auth.oauth_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->string('provider', 32);
            $table->text('provider_user_id');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestampTz('expires_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['provider', 'provider_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth.oauth_accounts');
        Schema::dropIfExists('auth.email_verification_tokens');
        Schema::dropIfExists('auth.password_reset_tokens');
        Schema::dropIfExists('auth.sessions');
    }
};
