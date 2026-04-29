<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth.otp_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->string('phone', 20);
            $table->string('otp_code', 64); // SHA-256 hash of the 6-digit code
            $table->string('type', 30)->default('registration');
            $table->boolean('is_used')->default(false);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth.otp_verifications');
    }
};
