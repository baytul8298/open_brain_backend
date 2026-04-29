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
        Schema::create('sub_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_module_id')->nullable()->after('sub_module');

            $table->foreign('sub_module_id')
                ->references('id')
                ->on('sub_modules')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['sub_module_id']);
            $table->dropColumn('sub_module_id');
        });

        Schema::dropIfExists('sub_modules');
    }
};
