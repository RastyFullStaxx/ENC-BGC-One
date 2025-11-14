import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing.css',
                'resources/js/landing.js',
                'resources/css/wizard.css',
                'resources/js/wizard.js',
                'resources/css/design-system.css',
            ],
            refresh: true,
        }),
    ],
});
