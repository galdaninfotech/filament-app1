import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/fireworks.css',
                'resources/js/app.js',
                'resources/js/fireworks.js',
                `resources/css/filament/admin/theme.css`,
            ],
            refresh: [
                'app/Livewire/**',
            ],
        }),
    ],
});
