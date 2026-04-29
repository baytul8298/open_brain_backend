<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates all per-row detail tables needed by the teacher profile page sections:
 *   - core.teacher_educations      → Education & Certificate
 *   - core.teacher_experiences     → Teacher Experience
 *   - core.teacher_subjects        → Subjects I Teach (teacher ↔ subject pivot)
 *   - core.teacher_grade_levels    → Grade Levels taught (teacher ↔ grade_level pivot)
 *   - core.teacher_teaching_styles → Teaching Style cards
 *   - core.teacher_availability    → Weekly Availability slots
 *   - core.teacher_verifications   → Verification submission history
 *
 * Also adds missing columns to core.teacher_profiles:
 *   - session_types  (jsonb)  → one-on-one / group session preferences
 *   - is_flexible_time (bool) → "Available anytime" toggle in Weekly Availability
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Education & Certificate ───────────────────────────────────────────
        // Replaces the qualifications jsonb blob with proper relational rows.
        // type: 'degree' | 'certificate'
        Schema::create('core.teacher_educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id')->index();
            $table->string('type', 20);                      // 'degree' | 'certificate'
            $table->string('title', 200);                    // degree/certificate name
            $table->string('institution', 200)->nullable();
            $table->smallInteger('start_year')->nullable();
            $table->smallInteger('end_year')->nullable();
            $table->boolean('is_current')->default(false);   // "currently studying"
            $table->smallInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        // ── Teacher Experience ────────────────────────────────────────────────
        // One row representing the teacher's primary/current teaching role.
        // role_type mirrors the role-selector options in the UI.
        Schema::create('core.teacher_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id')->index();
            $table->string('role_type', 30);                 // teacher | tutor | expert | freelance | student | other
            $table->tinyInteger('start_month')->nullable();  // 1–12
            $table->smallInteger('start_year')->nullable();
            $table->text('description')->nullable();
            $table->jsonb('expertise_tags')->default('[]'); // e.g. ["Mathematics","Exam Strategy"]
            $table->boolean('is_current')->default(true);
            $table->timestampsTz();
        });

        // ── Subjects I Teach — subject pivot ─────────────────────────────────
        Schema::create('core.teacher_subjects', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->smallInteger('subject_id');
            $table->primary(['user_id', 'subject_id']);
            $table->timestampTz('created_at')->useCurrent();
        });

        // ── Subjects I Teach — grade level pivot ─────────────────────────────
        Schema::create('core.teacher_grade_levels', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->integer('grade_level_id');
            $table->primary(['user_id', 'grade_level_id']);
            $table->timestampTz('created_at')->useCurrent();
        });

        // ── Teaching Style cards ──────────────────────────────────────────────
        // color is a short token (blue | green | gold | purple | teal | rust …)
        // matching the CSS class suffixes used in the UI (tsii-{color}).
        Schema::create('core.teacher_teaching_styles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id')->index();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color', 20)->default('blue');
            $table->smallInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        // ── Weekly Availability slots ─────────────────────────────────────────
        // One row per available time slot.
        // day_of_week: 0=Sun … 6=Sat  (ISO: Mon=1, but BD week starts Sat so UI
        //   shows Sat–Fri; store as 0=Sun,1=Mon,…,6=Sat to match PHP date('w')).
        // time_slot: stored as TIME (e.g. 16:00:00 for "4:00 PM").
        Schema::create('core.teacher_availability', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id')->index();
            $table->tinyInteger('day_of_week');  // 0 Sun … 6 Sat
            $table->time('time_slot');
            $table->unique(['user_id', 'day_of_week', 'time_slot']);
            $table->timestampTz('created_at')->useCurrent();
        });

        // ── Teacher Verification submissions ──────────────────────────────────
        // Tracks each document upload / review cycle.
        // status: pending | under_review | approved | rejected
        // doc_type mirrors the <select> options in the verification modal:
        //   ssc | hsc | honors | masters
        Schema::create('core.teacher_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id')->index();
            $table->string('doc_type', 30);                  // ssc | hsc | honors | masters
            $table->text('doc_url');                         // stored file path / S3 key
            $table->string('status', 20)->default('pending'); // pending | under_review | approved | rejected
            $table->uuid('reviewed_by')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestampTz('reviewed_at')->nullable();
            $table->timestampTz('submitted_at')->useCurrent();
        });

        // ── Add missing columns to core.teacher_profiles ──────────────────────
        Schema::table('core.teacher_profiles', function (Blueprint $table) {
            // Session types the teacher offers (one-on-one, group, etc.)
            // Stored as a jsonb array of string keys matching UI data-session values.
            $table->jsonb('session_types')->default('[]')->after('languages_taught');

            // "Available anytime" toggle in the Weekly Availability section.
            // When true the teacher_availability rows are ignored.
            $table->boolean('is_flexible_time')->default(false)->after('session_types');
        });
    }

    public function down(): void
    {
        Schema::table('core.teacher_profiles', function (Blueprint $table) {
            $table->dropColumn(['session_types', 'is_flexible_time']);
        });

        Schema::dropIfExists('core.teacher_verifications');
        Schema::dropIfExists('core.teacher_availability');
        Schema::dropIfExists('core.teacher_teaching_styles');
        Schema::dropIfExists('core.teacher_grade_levels');
        Schema::dropIfExists('core.teacher_subjects');
        Schema::dropIfExists('core.teacher_experiences');
        Schema::dropIfExists('core.teacher_educations');
    }
};
