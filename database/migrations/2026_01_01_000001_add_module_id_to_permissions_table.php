<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add module_id column as nullable
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->after('id');
            $table->index('module_id');
        });

        // Step 2: Get the first module as default
        $defaultModule = DB::table('modules')->first();

        // Step 3: Assign all existing permissions to the default module
        if ($defaultModule) {
            DB::table('permissions')
                ->whereNull('module_id')
                ->update(['module_id' => $defaultModule->id]);
        }

        // Step 4: Make module_id NOT NULL and add foreign key
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable(false)->change();

            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['module_id']);

            // Drop index
            $table->dropIndex(['module_id']);

            // Drop column
            $table->dropColumn('module_id');
        });
    }
};
