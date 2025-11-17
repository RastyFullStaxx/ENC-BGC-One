import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing/base.css',
                'resources/css/landing/hero.css',
                'resources/css/landing/availability.css',
                'resources/css/landing/facilities.css',
                'resources/css/landing/how-it-works.css',
                'resources/css/landing/policies.css',
                'resources/css/landing/cta.css',
                'resources/js/landing.js',
                'resources/css/wizard/base.css',
                'resources/css/wizard/step1.css',
                'resources/css/wizard/steps.css',
                'resources/css/wizard/bookings.css',
                'resources/js/wizard.js',
                'resources/css/design-system.css',
                'resources/css/user/account.css',
                'resources/css/wizard/base.css',
                'resources/css/admin/users.css',
                'resources/css/admin/facilities.css',
                'resources/css/admin/calendar.css',
                'resources/css/admin/policies.css',
                'resources/css/admin/audit.css',
                'resources/css/admin/analytics.css',
                'resources/css/facility/catalog.css',
                'resources/js/facility-catalog.js',
                'resources/css/user/faq/faq.css',
                 'resources/css/bookings/index.css',
                 'resources/css/bookings/show.css',
                 'resources/css/admin/dashboard.css',
                 'resources/css/admin/hub.css',
            ],
            refresh: true,
        }),
    ],
});
