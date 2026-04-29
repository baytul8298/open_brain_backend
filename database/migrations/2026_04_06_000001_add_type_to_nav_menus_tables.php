<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nav_parent_menus', function (Blueprint $table) {
            $table->enum('type', ['admin', 'teacher', 'student'])->default('admin')->after('name');
        });

        Schema::table('nav_menus', function (Blueprint $table) {
            $table->enum('type', ['admin', 'teacher', 'student'])->default('admin')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('nav_parent_menus', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
