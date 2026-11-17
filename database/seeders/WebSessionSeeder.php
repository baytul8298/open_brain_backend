<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSessionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::where('email', 'leo.baytul@gmail.com')->first();
        $superAdminId = $superAdmin?->id;

        $sessions = [
            [
                'id'            => '4hzjsCQUpDs8ASSsFkGkwKo8R3QvgW1I8UQrLix1',
                'user_id'       => $superAdminId,
                'ip_address'    => '127.0.0.1',
                'user_agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36',
                'payload'       => 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ09IaGc4UDF1QkRYTkV1bktMVk94ZzNPRE5YdW12WFRBVGU1UlNPUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6ImUxZGQ0OWFkLWZlYzYtNDJiNy1iODcwLTNiNzIzMmIyZmJlZCI7czoxNjoibGFzdF9hY3Rpdml0eV9hdCI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI2LTExLTEwIDE1OjA2OjM3LjU4MzY1MCI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fQ==',
                'last_activity' => time(),
            ],
            [
                'id'            => 'cB6zFRSthyLnkgyTpo17hoJSA17LCUqA2465dJuR',
                'user_id'       => $superAdminId,
                'ip_address'    => '127.0.0.1',
                'user_agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',
                'payload'       => 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUGU3WWJwSklKbElLT1hPTld0S1VxNTdjOG5pTXFiZTN4Sk5TZzVwRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6ImUxZGQ0OWFkLWZlYzYtNDJiNy1iODcwLTNiNzIzMmIyZmJlZCI7czoxNjoibGFzdF9hY3Rpdml0eV9hdCI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI2LTEwLTI3IDE5OjA5OjQxLjM2MDQ0NiI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fQ==',
                'last_activity' => time() - 3600,
            ],
            [
                'id'            => 'TKyYAmKSfCG5fioKosHzdiEW64FxJjNckGEwQ0aD',
                'user_id'       => null,
                'ip_address'    => '127.0.0.1',
                'user_agent'    => 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456',
                'payload'       => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnpFeVByZzZwVjVIYXNwbTkydkJta3A2UGJLeHJmaEY4SG4wa2VsVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9vcGVuX2JyYWluLnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',
                'last_activity' => time() - 7200,
            ],
        ];

        foreach ($sessions as $session) {
            DB::table('web_sessions')->updateOrInsert(
                ['id' => $session['id']],
                $session
            );
        }

        $this->command?->info('Seeded ' . DB::table('web_sessions')->count() . ' web sessions.');
    }
}
