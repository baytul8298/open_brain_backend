<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('core.grade_level', function (Blueprint $table) {
            $table->string('name_bn', 120)->nullable()->after('name');
            $table->string('slug', 255)->nullable()->after('name_bn');
            $table->string('icon', 255)->nullable()->after('slug');
            $table->string('color', 255)->nullable()->after('icon');
            $table->tinyInteger('sort_order')->nullable()->default(0)->after('color');
            $table->boolean('is_active')->nullable()->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('core.grade_level', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'slug', 'icon', 'color', 'sort_order', 'is_active']);
        });
    }
};
