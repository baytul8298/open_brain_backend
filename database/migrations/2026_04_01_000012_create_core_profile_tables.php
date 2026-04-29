<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Documents core profile/content tables created via SQL (registered as fake). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core.profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('display_name', 120)->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('cover_url')->nullable();
            $table->text('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 16)->nullable();
            $table->char('country', 2)->default('BD');
            $table->string('city', 80)->nullable();
            $table->string('timezone', 64)->default('Asia/Dhaka');
            $table->string('language', 8)->default('bn');
            $table->jsonb('notifications_prefs')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('core.student_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->integer('grade_level_id')->nullable();
            $table->string('school_name', 200)->nullable();
            $table->string('board', 50)->nullable();
            $table->string('roll_number', 30)->nullable();
            $table->string('registration_number', 30)->nullable();
            $table->string('parent_name', 160)->nullable();
            $table->string('parent_phone', 20)->nullable();
            $table->string('parent_email', 320)->nullable();
            $table->jsonb('interested_subjects')->nullable();
            $table->integer('points')->default(0);
            $table->timestampTz('last_activity_at')->nullable();
            $table->timestampTz('updated_at')->useCurrent();
        });

        Schema::create('core.teacher_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->integer('teacher_type_id')->default(1);
            $table->string('headline', 200)->nullable();
            $table->text('specializations')->nullable();
            $table->jsonb('qualifications')->nullable();
            $table->smallInteger('experience_years')->nullable();
            $table->string('institution', 200)->nullable();
            $table->string('department', 200)->nullable();
            $table->boolean('verified')->default(false);
            $table->timestampTz('verified_at')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->text('id_document_url')->nullable();
            $table->boolean('id_verified')->default(false);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('total_students')->default(0);
            $table->smallInteger('total_courses')->default(0);
            $table->decimal('total_revenue', 14, 2)->default(0);
            $table->jsonb('payout_account')->nullable();
            $table->decimal('payout_threshold', 10, 2)->default(500);
            $table->decimal('commission_rate', 5, 4)->default(0.15);
            $table->boolean('is_featured')->default(false);
            $table->jsonb('social_links')->default('{}');
            $table->text('languages_taught')->default('{1}');
            $table->timestampTz('updated_at')->useCurrent();
        });

        Schema::create('core.subjects', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 120)->unique();
            $table->string('slug', 120)->unique();
            $table->string('name_bn', 120)->nullable();
            $table->string('icon', 64)->nullable();
            $table->char('color', 7)->nullable();
            $table->smallInteger('parent_id')->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('core.media', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('uploader_id');
            $table->string('media_type', 20);
            $table->string('original_name');
            $table->text('storage_key')->unique();
            $table->text('public_url');
            $table->text('cdn_url')->nullable();
            $table->string('mime_type', 127);
            $table->bigInteger('size_bytes');
            $table->integer('width_px')->nullable();
            $table->integer('height_px')->nullable();
            $table->integer('duration_sec')->nullable();
            $table->jsonb('metadata')->default('{}');
            $table->boolean('is_public')->default(false);
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.media');
        Schema::dropIfExists('core.subjects');
        Schema::dropIfExists('core.teacher_profiles');
        Schema::dropIfExists('core.student_profiles');
        Schema::dropIfExists('core.profiles');
    }
};
