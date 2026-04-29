<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Documents the core lookup/reference tables that were created directly via SQL.
 * This migration is registered as already run (fake) — tables exist in the DB.
 */
return new class extends Migration
{
    public function up(): void
    {
        // core.teacher_type
        Schema::create('core.teacher_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestampsTz();
        });

        // core.course_format
        Schema::create('core.course_format', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestampsTz();
        });

        // core.lesson_type
        Schema::create('core.lesson_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestampsTz();
        });

        // core.payment_method
        Schema::create('core.payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestampsTz();
        });

        // core.notification_type
        Schema::create('core.notification_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestampsTz();
        });

        // core.grade_level
        Schema::create('core.grade_level', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestampsTz();
        });

        // core.language_medium
        Schema::create('core.language_medium', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.language_medium');
        Schema::dropIfExists('core.grade_level');
        Schema::dropIfExists('core.notification_type');
        Schema::dropIfExists('core.payment_method');
        Schema::dropIfExists('core.lesson_type');
        Schema::dropIfExists('core.course_format');
        Schema::dropIfExists('core.teacher_type');
    }
};
