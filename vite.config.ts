import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@/components': path.resolve(__dirname, './resources/js/Components'),
            '@/layouts': path.resolve(__dirname, './resources/js/Layouts'),
            '@/pages': path.resolve(__dirname, './resources/js/Pages'),
            '@/composables': path.resolve(__dirname, './resources/js/composables'),
            '@/stores': path.resolve(__dirname, './resources/js/stores'),
            '@/types': path.resolve(__dirname, './resources/js/types'),
            '@/utils': path.resolve(__dirname, './resources/js/utils'),
            '@/services': path.resolve(__dirname, './resources/js/services'),
            '@/config': path.resolve(__dirname, './resources/js/config'),
            '@/lib': path.resolve(__dirname, './resources/js/lib'),
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
