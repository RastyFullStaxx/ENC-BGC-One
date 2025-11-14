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
                'resources/css/wizard/base.css',
                'resources/css/wizard/step1.css',
                'resources/css/wizard/steps.css',
                'resources/css/wizard/bookings.css',
                'resources/js/wizard.js',
            ],
            refresh: true,
        }),
    ],
});
