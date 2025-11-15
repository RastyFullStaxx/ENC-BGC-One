# ENC BGC One

Smart booking and shared-services portal for Every Nation Campus BGC. The project centralizes room reservations, shuttle requests, facility policies, and onboarding content in one responsive Laravel/Vite application.

## Overview

ENC BGC One streamlines how teams discover, request, and track shared resources inside the campus. The marketing landing page showcases the product story, while the Laravel 12 backend, Blade views, and Vite-powered frontend set the foundation for the authenticated experience (login, registration, approvals, audit trail, and more). Bootstrap 5 components, enhanced with custom tokens defined in the modular `resources/css/landing/` styles, keep the interface on brand across devices.

## Feature Highlights

- Story-driven landing page that walks visitors through availability stats, featured facilities, booking policies, and strong CTAs before sign-up (esources/views/marketing/landing.blade.php).
- Real-time inspired widgets such as the "Quick Availability Glance" heat bars, recommended slots, and free-room cards (animated by esources/js/landing.js).
- Reusable navbar and footer partials that keep branding, accessibility, and support information consistent across future pages (esources/views/partials).
- Accessibility polish out of the box: focus-visible styles, reduced-motion fallbacks, smooth keyboard scrolling, and idle header/footer treatment all live in the landing CSS modules and `resources/js/landing.js`.
- Modern asset pipeline backed by Vite 6, ES modules, and Bootstrap 5.3.8 with room to add Tailwind 4 utilities through @tailwindcss/vite.
- Laravel 12 foundation with routing (outes/web.php), queue-ready scripts, Laravel Pint, PHPUnit, Sail, Pail, and other first-party tooling already configured in composer.json.

## Tech Stack

| Layer      | Tools |
| ---------- | ----- |
| Framework  | Laravel 12, PHP 8.2, Blade templating, Artisan CLI |
| Frontend   | Vite 6, Bootstrap 5.3, ES modules in esources/js, custom design tokens |
| Tooling    | Composer, npm, Laravel Pint, PHPUnit 11, Pail log streaming, concurrently |
| Data layer | SQLite/MySQL/PostgreSQL via Laravel database drivers (SQLite bootstrap script included) |

## Getting Started

### 1. Prerequisites

- PHP 8.2+ with common extensions (openssl, pdo, mbstring, tokenizer, xml, ctype, json, bcmath)
- Composer 2.6+
- Node.js 20 LTS (or 18+) and npm 10+
- SQLite (default) or MySQL/PostgreSQL instance

### 2. Installation

`ash
git clone https://github.com/<org>/ENC-BGC-One.git
cd ENC-BGC-One
composer install
npm install
cp .env.example .env
php artisan key:generate
`

Update .env with database, cache, queue, and mail credentials. The Composer post-create script also prepares database/database.sqlite and runs initial migrations when permissions allow.

### 3. Database

`ash
# SQLite (default)
touch database/database.sqlite
php artisan migrate

# or use another database configured in .env
php artisan migrate --seed
`

### 4. Local Development

`ash
# Manual workflow
php artisan serve          # API and Blade pages
php artisan queue:listen   # if you queue jobs
npm run dev                # Vite dev server with HMR

# All-in-one helper defined in composer.json
composer run dev
`

### 5. Production Build

`ash
npm run build
php artisan config:cache
php artisan route:cache
php artisan queue:restart
`

Deploy the public/ assets together with compiled configuration caches to your hosting platform (Forge, Vapor, Docker, etc.).

## Notable Directories

- esources/views/marketing/landing.blade.php – marketing page with modular sections and placeholder analytics data.
- `resources/css/landing/` – design tokens, responsive layout rules, and per-section styles (hero, availability, facilities, policies, CTA).
- esources/js/landing.js – Bootstrap import plus smooth scrolling, idle header/footer logic, and IntersectionObserver-based animations.
- esources/views/partials – reusable navbar and footer components with configurable links.
- outes/web.php – current public routes (landing plus auth placeholders) with clear extension points for additional modules.

## Quality and Tooling

- Testing: PHPUnit 11 ships by default; add HTTP/feature tests under 	ests/Feature for booking workflows, approval routes, and API guards.
- Code style: run endor/bin/pint before committing to keep PHP files consistent.
- Observability: laravel/pail provides real-time log streaming; wire it into centralized logging for staging and production.

## Contributing

1. Fork the repository and create a descriptive branch (git checkout -b feature/availability-api).
2. Keep Blade, CSS, and JS changes scoped with clear commits.
3. Add or update automated tests when behavior changes.
4. Run php artisan test and 
pm run build (or at least 
pm run dev without errors) before opening a pull request.
5. Use semantic PR titles such as eat: expose shuttle booking metrics API.

## Roadmap Ideas

- Connect availability charts to live facility data (polling or WebSocket streams).
- Add multi-step approvals with configurable SLAs per facility.
- Send Teams or Slack notifications for booking confirmations and reminders.
- Expand analytics dashboards with historical utilization trends and export tools.

## License

The project inherits the default Laravel MIT license. Replace or extend it if your organization requires a different policy.
