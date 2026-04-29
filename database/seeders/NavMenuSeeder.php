<?php

namespace Database\Seeders;

use App\Models\NavMenu;
use App\Models\NavParentMenu;
use App\Models\NavSubmenu;
use Illuminate\Database\Seeder;

class NavMenuSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Overview ───────────────────────────────────────────────
        $overview = NavParentMenu::updateOrCreate(
            ['name' => 'Overview'],
            ['guard' => 'web', 'sort_order' => 1, 'is_active' => true]
        );

        $analytics = NavMenu::updateOrCreate(
            ['key' => 'analytics'],
            ['parent_menu_id' => $overview->id, 'name' => 'Analytics', 'icon_key' => 'chart-bar', 'to' => '/analytics', 'sort_order' => 1, 'is_active' => true]
        );

        $users = NavMenu::updateOrCreate(
            ['key' => 'users'],
            ['parent_menu_id' => $overview->id, 'name' => 'Users', 'icon_key' => 'users', 'to' => '/users', 'sort_order' => 2, 'is_active' => true]
        );

        // ── 2. Academic ───────────────────────────────────────────────
        $academic = NavParentMenu::updateOrCreate(
            ['name' => 'Academic'],
            ['guard' => 'web', 'sort_order' => 2, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'teachers'],
            ['parent_menu_id' => $academic->id, 'name' => 'Teachers', 'icon_key' => 'user-tie', 'to' => '/teachers', 'sort_order' => 1, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'courses'],
            ['parent_menu_id' => $academic->id, 'name' => 'Courses', 'icon_key' => 'book-open', 'to' => '/courses', 'sort_order' => 2, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'subjects'],
            ['parent_menu_id' => $academic->id, 'name' => 'Subjects', 'icon_key' => 'layers', 'to' => '/subjects', 'sort_order' => 3, 'is_active' => true]
        );

        $enrollments = NavMenu::updateOrCreate(
            ['key' => 'enrollments'],
            ['parent_menu_id' => $academic->id, 'name' => 'Enrollments', 'icon_key' => 'clipboard-list', 'to' => null, 'sort_order' => 4, 'is_active' => true]
        );

        NavSubmenu::updateOrCreate(
            ['menu_id' => $enrollments->id, 'name' => 'All Enrollments'],
            ['to' => '/enrollments', 'sort_order' => 1, 'is_active' => true]
        );

        NavSubmenu::updateOrCreate(
            ['menu_id' => $enrollments->id, 'name' => 'Assignments'],
            ['to' => '/assignments', 'sort_order' => 2, 'is_active' => true]
        );

        NavSubmenu::updateOrCreate(
            ['menu_id' => $enrollments->id, 'name' => 'Live Sessions'],
            ['to' => '/live-sessions', 'sort_order' => 3, 'is_active' => true]
        );

        NavSubmenu::updateOrCreate(
            ['menu_id' => $enrollments->id, 'name' => 'Reviews'],
            ['to' => '/reviews', 'sort_order' => 4, 'is_active' => true]
        );

        // ── 3. Finance ────────────────────────────────────────────────
        $finance = NavParentMenu::updateOrCreate(
            ['name' => 'Finance'],
            ['guard' => 'web', 'sort_order' => 3, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'payments'],
            ['parent_menu_id' => $finance->id, 'name' => 'Payments', 'icon_key' => 'credit-card', 'to' => '/payments', 'sort_order' => 1, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'payouts'],
            ['parent_menu_id' => $finance->id, 'name' => 'Payouts', 'icon_key' => 'arrow-up-right', 'to' => '/payouts', 'sort_order' => 2, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'coupons'],
            ['parent_menu_id' => $finance->id, 'name' => 'Coupons', 'icon_key' => 'tag', 'to' => '/coupons', 'sort_order' => 3, 'is_active' => true]
        );

        // ── 4. Operations ─────────────────────────────────────────────
        $ops = NavParentMenu::updateOrCreate(
            ['name' => 'Operations'],
            ['guard' => 'web', 'sort_order' => 4, 'is_active' => true]
        );

        NavMenu::updateOrCreate(
            ['key' => 'moderation'],
            ['parent_menu_id' => $ops->id, 'name' => 'Moderation', 'icon_key' => 'shield', 'to' => '/moderation', 'sort_order' => 1, 'is_active' => true]
        );
    }
}
