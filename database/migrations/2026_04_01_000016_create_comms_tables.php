<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Documents comms schema tables created via SQL (registered as fake). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comms.reviews', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('course_id');
            $table->uuid('teacher_id');
            $table->uuid('student_id');
            $table->uuid('enrollment_id');
            $table->smallInteger('rating');
            $table->string('title', 120)->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->text('reply')->nullable();
            $table->timestampTz('reply_at')->nullable();
            $table->timestampsTz();
            $table->unique(['course_id', 'student_id']);
        });

        Schema::create('comms.announcements', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('teacher_id');
            $table->uuid('course_id')->nullable();
            $table->string('title', 200);
            $table->text('body');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('send_email')->default(true);
            $table->boolean('send_push')->default(true);
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('comms.notifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->integer('type_id');
            $table->string('title', 200);
            $table->text('body')->nullable();
            $table->jsonb('data')->default('{}');
            $table->text('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestampTz('read_at')->nullable();
            $table->timestampTz('sent_at')->useCurrent();
            $table->boolean('email_sent')->default(false);
            $table->boolean('push_sent')->default(false);
            $table->boolean('sms_sent')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comms.notifications');
        Schema::dropIfExists('comms.announcements');
        Schema::dropIfExists('comms.reviews');
    }
};
