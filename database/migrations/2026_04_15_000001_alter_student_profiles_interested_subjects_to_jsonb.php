<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop and recreate as jsonb regardless of the current column type
        // (handles text[], integer[], or text with array literals)
        DB::statement('ALTER TABLE core.student_profiles DROP COLUMN IF EXISTS interested_subjects');
        DB::statement('ALTER TABLE core.student_profiles ADD COLUMN interested_subjects jsonb');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE core.student_profiles DROP COLUMN IF EXISTS interested_subjects');
        DB::statement('ALTER TABLE core.student_profiles ADD COLUMN interested_subjects text');
    }
};
