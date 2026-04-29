<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_parent_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard')->default('web');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('nav_menus', function (Blueprint $table) {
            $table->foreignId('parent_menu_id')
                ->nullable()
                ->after('id')
                ->constrained('nav_parent_menus')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\NavParentMenu::class);
            $table->dropColumn('parent_menu_id');
        });

        Schema::dropIfExists('nav_parent_menus');
    }
};
