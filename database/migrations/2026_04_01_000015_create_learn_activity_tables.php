<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Documents learn activity/progress tables created via SQL (registered as fake). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learn.lesson_progress', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('student_id');
            $table->uuid('lesson_id');
            $table->uuid('course_id');
            $table->uuid('enrollment_id');
            $table->boolean('is_completed')->default(false);
            $table->integer('watch_seconds')->default(0);
            $table->integer('last_position_sec')->default(0);
            $table->timestampTz('completed_at')->nullable();
            $table->timestampTz('first_opened_at')->nullable();
            $table->timestampTz('last_opened_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->unique(['student_id', 'lesson_id']);
        });

        Schema::create('learn.quiz_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('student_id');
            $table->uuid('quiz_id');
            $table->uuid('enrollment_id');
            $table->smallInteger('attempt_number')->default(1);
            $table->timestampTz('started_at')->useCurrent();
            $table->timestampTz('submitted_at')->nullable();
            $table->integer('time_taken_sec')->nullable();
            $table->smallInteger('score')->nullable();
            $table->smallInteger('max_score');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->jsonb('answers')->default('[]');
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('learn.assignments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id');
            $table->uuid('teacher_id');
            $table->uuid('section_id')->nullable();
            $table->string('title', 200);
            $table->text('description');
            $table->text('instructions')->nullable();
            $table->jsonb('attachments')->default('[]');
            $table->string('status', 20)->default('draft');
            $table->timestampTz('due_date')->nullable();
            $table->smallInteger('total_marks')->default(100);
            $table->smallInteger('pass_marks')->default(40);
            $table->boolean('allow_late_submit')->default(false);
            $table->smallInteger('late_penalty_pct')->default(0);
            $table->smallInteger('max_file_size_mb')->default(10);
            $table->text('allowed_file_types')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('learn.assignment_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('assignment_id');
            $table->uuid('student_id');
            $table->uuid('enrollment_id');
            $table->string('status', 20)->default('not_submitted');
            $table->timestampTz('submitted_at')->nullable();
            $table->boolean('is_late')->default(false);
            $table->jsonb('files')->default('[]');
            $table->text('student_note')->nullable();
            $table->smallInteger('marks')->nullable();
            $table->string('grade', 4)->nullable();
            $table->text('feedback')->nullable();
            $table->uuid('graded_by')->nullable();
            $table->timestampTz('graded_at')->nullable();
            $table->timestampTz('returned_at')->nullable();
            $table->boolean('resubmit_allowed')->default(false);
            $table->smallInteger('resubmit_count')->default(0);
            $table->timestampsTz();
            $table->unique(['assignment_id', 'student_id']);
        });

        Schema::create('learn.certificates', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->uuid('enrollment_id');
            $table->string('status', 20)->default('not_earned');
            $table->string('certificate_number', 32)->nullable()->unique();
            $table->timestampTz('issued_at')->nullable();
            $table->text('pdf_url')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['student_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learn.certificates');
        Schema::dropIfExists('learn.assignment_submissions');
        Schema::dropIfExists('learn.assignments');
        Schema::dropIfExists('learn.quiz_attempts');
        Schema::dropIfExists('learn.lesson_progress');
    }
};
