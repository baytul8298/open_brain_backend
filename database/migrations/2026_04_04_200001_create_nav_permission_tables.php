<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_nav_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('nav_item_type', 20); // menu | submenu | button
            $table->unsignedBigInteger('nav_item_id');
            $table->timestamps();

            $table->unique(['role_id', 'nav_item_type', 'nav_item_id'], 'rnp_unique');
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });

        Schema::create('user_nav_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('nav_item_type', 20); // menu | submenu | button
            $table->unsignedBigInteger('nav_item_id');
            $table->boolean('is_granted')->default(true); // true=grant override, false=revoke override
            $table->timestamps();

            $table->unique(['user_id', 'nav_item_type', 'nav_item_id'], 'unp_unique');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_nav_permissions');
        Schema::dropIfExists('role_nav_permissions');
    }
};
