<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Documents learn schema tables created via SQL (registered as fake). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learn.courses', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('teacher_id');
            $table->smallInteger('subject_id');
            $table->string('title', 120);
            $table->string('slug', 140)->unique();
            $table->string('short_description', 300);
            $table->text('full_description')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('trailer_url')->nullable();
            $table->text('grade_level_ids')->default('{}');
            $table->string('board', 30)->nullable();
            $table->integer('language_id')->default(1);
            $table->integer('format_id')->default(1);
            $table->string('status', 20)->default('draft');
            $table->text('tags')->default('{}');
            $table->string('pricing_model', 30)->default('monthly_subscription');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->char('currency', 3)->default('BDT');
            $table->integer('max_students')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->smallInteger('session_duration_min')->default(60);
            $table->integer('enrolled_count')->default(0);
            $table->integer('completed_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->smallInteger('total_lessons')->default(0);
            $table->smallInteger('total_sections')->default(0);
            $table->integer('total_duration_min')->default(0);
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
            $table->timestampTz('deleted_at')->nullable();
        });

        Schema::create('learn.course_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('course_id');
            $table->string('type', 16);
            $table->text('content');
            $table->smallInteger('sort_order')->default(0);
        });

        Schema::create('learn.sections', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->timestampsTz();
        });

        Schema::create('learn.lessons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('section_id');
            $table->uuid('course_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->integer('lesson_type_id')->default(1);
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->boolean('is_published')->default(false);
            $table->text('video_url')->nullable();
            $table->integer('video_duration_sec')->nullable();
            $table->text('document_url')->nullable();
            $table->smallInteger('document_pages')->nullable();
            $table->text('external_url')->nullable();
            $table->jsonb('content_json')->nullable();
            $table->timestampTz('scheduled_at')->nullable();
            $table->string('live_platform', 32)->nullable();
            $table->text('live_meeting_url')->nullable();
            $table->string('live_meeting_id', 128)->nullable();
            $table->string('live_password', 64)->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->jsonb('attachments')->default('[]');
            $table->timestampsTz();
        });

        Schema::create('learn.quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('lesson_id');
            $table->uuid('course_id');
            $table->string('title', 200)->nullable();
            $table->text('instructions')->nullable();
            $table->smallInteger('time_limit_min')->nullable();
            $table->smallInteger('pass_percentage')->default(60);
            $table->smallInteger('attempts_allowed')->default(3);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_answers_after')->default(true);
            $table->timestampsTz();
        });

        Schema::create('learn.quiz_questions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('quiz_id');
            $table->text('question_text');
            $table->text('question_image')->nullable();
            $table->string('question_type', 20)->default('mcq');
            $table->jsonb('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->smallInteger('points')->default(1);
            $table->smallInteger('sort_order')->default(0);
        });

        Schema::create('learn.course_schedule', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id');
            $table->string('day_of_week', 10);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('timezone', 64)->default('Asia/Dhaka');
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('learn.live_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id');
            $table->uuid('lesson_id')->nullable();
            $table->uuid('teacher_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('scheduled');
            $table->timestampTz('scheduled_start_at');
            $table->timestampTz('scheduled_end_at');
            $table->timestampTz('actual_start_at')->nullable();
            $table->timestampTz('actual_end_at')->nullable();
            $table->string('platform', 32)->default('learnforge');
            $table->text('meeting_url')->nullable();
            $table->string('meeting_id', 128)->nullable();
            $table->text('recording_url')->nullable();
            $table->boolean('recording_available')->default(false);
            $table->integer('attendee_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('learn.session_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('session_id');
            $table->uuid('student_id');
            $table->timestampTz('joined_at')->nullable();
            $table->timestampTz('left_at')->nullable();
            $table->smallInteger('duration_min')->nullable();
            $table->boolean('attended')->default(false);
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['session_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learn.session_attendance');
        Schema::dropIfExists('learn.live_sessions');
        Schema::dropIfExists('learn.course_schedule');
        Schema::dropIfExists('learn.quiz_questions');
        Schema::dropIfExists('learn.quizzes');
        Schema::dropIfExists('learn.lessons');
        Schema::dropIfExists('learn.sections');
        Schema::dropIfExists('learn.course_requirements');
        Schema::dropIfExists('learn.courses');
    }
};
