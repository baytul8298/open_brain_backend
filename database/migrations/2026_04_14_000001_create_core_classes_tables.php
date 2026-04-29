<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Creates core.classes (ClassRoom), core.class_schedule, and core.class_students tables. */
return new class extends Migration
{
    public function up(): void
    {
        // core.classes — a batch/cohort of students learning together (model: ClassRoom)
        Schema::create('core.classes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id')->nullable();         // FK → learn.courses (null = standalone class)
            $table->uuid('teacher_id');                    // FK → users
            $table->smallInteger('subject_id')->nullable(); // FK → core.subjects
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->text('description')->nullable();
            $table->string('class_type', 20)->default('live'); // live | recorded | hybrid
            $table->string('status', 20)->default('draft');    // draft | upcoming | active | completed | cancelled
            $table->integer('max_students')->nullable();
            $table->integer('enrolled_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('grade_level_ids')->default('{}');  // int[] → core.class_names ids
            $table->integer('language_id')->default(1);      // FK → core.language_medium
            $table->string('meeting_platform', 32)->nullable(); // zoom | google_meet | learnforge | etc.
            $table->text('meeting_url')->nullable();
            $table->string('meeting_id', 128)->nullable();
            $table->string('meeting_password', 64)->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->text('notes')->nullable();
            $table->uuid('created_by')->nullable();           // FK → users (admin/teacher who created)
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
            $table->timestampTz('deleted_at')->nullable();
        });

        // core.class_schedule — recurring weekly schedule slots for a class
        Schema::create('core.class_schedule', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('class_room_id');
            $table->string('day_of_week', 10); // monday | tuesday | ... | sunday
            $table->time('start_time');
            $table->time('end_time');
            $table->string('timezone', 64)->default('Asia/Dhaka');
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        // core.class_students — students enrolled/admitted in a class
        Schema::create('core.class_students', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('class_room_id');
            $table->uuid('student_id');
            $table->uuid('enrollment_id')->nullable(); // FK → commerce.enrollments (if paid)
            $table->string('status', 20)->default('active'); // active | suspended | completed | withdrawn
            $table->timestampTz('joined_at')->useCurrent();
            $table->timestampTz('left_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['class_room_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.class_students');
        Schema::dropIfExists('core.class_schedule');
        Schema::dropIfExists('core.classes');
    }
};
