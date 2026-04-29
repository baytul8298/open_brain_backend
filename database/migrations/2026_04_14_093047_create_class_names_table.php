<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('core.class_names', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 120);
            $table->string('name_bn', 120)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->tinyInteger('sort_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core.class_names');
    }
};
