<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nav_submenus', function (Blueprint $table) {
            if (!Schema::hasColumn('nav_submenus', 'icon_key')) {
                $table->string('icon_key')->nullable()->after('name');
            }
        });

        Schema::table('nav_buttons', function (Blueprint $table) {
            if (!Schema::hasColumn('nav_buttons', 'icon_key')) {
                $table->string('icon_key')->nullable()->after('key_value');
            }
            if (!Schema::hasColumn('nav_buttons', 'to')) {
                $table->string('to')->nullable()->after('icon_key');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nav_buttons', function (Blueprint $table) {
            $columns = array_filter(['icon_key', 'to'], fn ($col) => Schema::hasColumn('nav_buttons', $col));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('nav_submenus', function (Blueprint $table) {
            if (Schema::hasColumn('nav_submenus', 'icon_key')) {
                $table->dropColumn('icon_key');
            }
        });
    }
};
