<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CacheSeeder extends Seeder
{
    public function run(): void
    {
        $prefix = config('cache.prefix', 'laravel-cache-');
        $expiration = time() + 86400 * 30; // 30 days

        $cacheItems = [
            [
                'key'        => $prefix . 'system_settings',
                'value'      => serialize([
                    'site_name'        => 'OpenBrain Studio',
                    'default_language' => 'en',
                    'timezone'         => 'Asia/Dhaka',
                    'currency'         => 'BDT',
                ]),
                'expiration' => $expiration,
            ],
            [
                'key'        => $prefix . 'app_initialized',
                'value'      => serialize(true),
                'expiration' => $expiration,
            ],
        ];

        foreach ($cacheItems as $item) {
            DB::table('cache')->updateOrInsert(
                ['key' => $item['key']],
                [
                    'value'      => $item['value'],
                    'expiration' => $item['expiration'],
                ]
            );
        }

        $this->command?->info('Seeded ' . DB::table('cache')->count() . ' cache entries.');
    }
}
