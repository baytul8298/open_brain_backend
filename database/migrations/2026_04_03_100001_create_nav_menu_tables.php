<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('icon_key')->nullable();
            $table->string('to')->nullable(); // route, null for group menus
            $table->string('search_key')->nullable();
            $table->string('divider_title')->nullable();
            $table->string('guard')->default('web'); // web or admin
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nav_submenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('nav_menus')->cascadeOnDelete();
            $table->string('name');
            $table->string('search_key')->nullable();
            $table->string('to')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nav_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submenu_id')->constrained('nav_submenus')->cascadeOnDelete();
            $table->string('name');
            $table->string('key_value');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nav_buttons');
        Schema::dropIfExists('nav_submenus');
        Schema::dropIfExists('nav_menus');
    }
};
