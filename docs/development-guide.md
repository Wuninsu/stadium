# Aliu Mahama Sports Stadium: Development Guide

Stack: **Laravel 13** (PHP 8.3) · **Livewire 4** · **SQLite** · **Vite** · **Bootstrap 5** · **Bootstrap Icons** · **Font Awesome Free** · **Chart.js**

Design/UI source of truth: `public/project-ui/*.html` (19 static reference pages). Every Blade page must be built to visually match its corresponding reference file exactly, same markup structure, same CSS classes, same Bootstrap components.

> This guide was written 2026-10-06 after auditing the full `project-ui` reference set against the existing scaffold. It reflects what currently exists in the repo and lays out the remaining work, phase by phase.

---

## 1. Current State Snapshot

### What already exists
- Laravel 13 + Livewire 4 skeleton installed, SQLite DB configured, `database.sqlite` present.
- `package.json` already lists `bootstrap`, `bootstrap-icons`, `@fortawesome/fontawesome-free`, `chart.js` as dependencies, and they are installed in `node_modules`.
- `resources/css/app.css` imports Bootstrap, Bootstrap Icons, and Font Awesome. `resources/js/app.js` imports Bootstrap's JS bundle and registers Chart.js on `window.Chart`.
- Three Blade layouts exist and already reuse the reference's CSS custom properties (`--pitch-*`, `--flood-*`, `--chalk-*`, `--ink-*`):
  - `resources/views/layouts/main.blade.php`: public site shell (navbar + footer)
  - `resources/views/layouts/app.blade.php`: admin shell (sidebar, no topbar yet)
  - `resources/views/layouts/auth.blade.php`: centered auth card shell
- 8 routes and 8 Livewire page classes exist (`routes/web.php`, `app/Livewire/{Public,Auth,Admin}/*`), each paired with a Blade view: `HomePage`, `EventsPage`, `BookingPage`, `ContactPage`, `LoginPage`, `RegisterPage`, `DashboardPage`, `BookingsPage`.
- **Fixed in this pass:** the layouts were loading Bootstrap/Icons from a CDN and never actually included the Vite bundle (no `@vite(...)` anywhere), so Font Awesome and Chart.js were never loaded in the browser at all. `resources/js/app.js` also imported a `./bootstrap` file that didn't exist, which broke `npm run build`. Both are now fixed: all three layouts call `@vite(['resources/css/app.css', 'resources/js/app.js'])`, the redundant CDN tags were removed, and the dead import was dropped. `npm run build` now succeeds.

### What's missing or only a rough placeholder
None of the 8 existing Livewire classes contain real logic, each is just `return view(...)`. No `$rules`, no validation, no Eloquent queries; all "data" in the views is hardcoded markup. No domain migrations/models exist yet (only the default `users`, `cache`, `jobs` tables). Fidelity against the reference is low across the board:

| Reference page | Backing page | Status |
|---|---|---|
| `index.html` | `Public\HomePage` | Rough placeholder: missing scoreboard, animated counters, 4 feature cards, fixtures list, CTA band |
| `events.html` | `Public\EventsPage` | Rough placeholder: missing filter pills, search, attendee badges, pagination; 3 hardcoded events in an inline array |
| `booking.html` | `Public\BookingPage` | Rough placeholder: static HTML, no `wire:model`, missing availability sidebar, policy panel, confirmation modal, most fields |
| `contact.html` | `Public\ContactPage` | Rough placeholder: missing story/stats, subject select, map panel; no reactivity |
| `login.html` | `Auth\LoginPage` | Rough placeholder: generic card instead of split brand panel, no password toggle, no SSO button, no logic |
| `register.html` | `Auth\RegisterPage` | Rough placeholder: missing organisation field, password-strength meter, terms checkbox, OTP flow |
| `admin-dashboard.html` | `Admin\DashboardPage` | Rough placeholder: wrong shell entirely (no icons/topbar/collapse/user block), 3 generic cards, **no charts** |
| `admin-bookings.html` | `Admin\BookingsPage` | Rough placeholder: 2 hardcoded rows vs 179, no stat cards/filters/search/pagination, wrong badge styling |
| `admin-facilities.html` | none | **Missing**, route redirects to `/admin` |
| `admin-events.html` | none | **Missing**, route redirects to `/admin` |
| `admin-reports.html` | none | **Missing**, route redirects to `/admin` |
| `admin-settings.html` | none | **Missing**, route redirects to `/admin` |
| `admin-staff.html` | none | **Missing**, route redirects to `/admin` |
| `admin-teams.html` | none | **Missing**, route redirects to `/admin` |
| `forgot-password.html` | none | **Missing**, no route |
| `reset-password.html` | none | **Missing**, no route |
| `verify-otp.html` | none | **Missing**, no route |
| `403.html` / `404.html` / `500.html` | none | **Missing**, Laravel default error views not overridden |

Also worth knowing: `vite.config.js` still carries the Laravel starter kit defaults of `@tailwindcss/vite` and a `bunny('Instrument Sans', …)` web font. Neither is used anywhere (no Tailwind classes, no reference to Instrument Sans; the real design fonts, Space Grotesk/Inter/JetBrains Mono, are pulled from a Google Fonts `<link>` in each layout's `<head>`). Safe to remove both once you're ready to make the build config match reality (not required, just dead weight today).

---

## 2. Design System (from `project-ui`)

All 19 reference pages share one inline design system. When you port this into the real app, move it out of inline `<style>` blocks and into `resources/css/app.css` (or a `resources/css/_design-system.css` partial imported from `app.css`) so every layout pulls from one copy.

**Colors** (CSS custom properties):
```
--pitch-900:#063D2C  --pitch-800:#084A36  --pitch-700:#0B6E4F  --pitch-600:#118A63  --pitch-500:#16A075  --pitch-100:#E3F3EC
--flood-400:#FFC72C  --flood-500:#F5B800  --flood-600:#E6A800
--chalk-50:#F6F9F6   --chalk-100:#EDF3EE  --chalk-200:#E1EAE4
--ink-900:#10231C    --ink-700:#25382F    --ink-600:#4B615A   --ink-400:#7C8F87
--line:#DDE7E0       --danger:#D64545
```

**Typography:** Space Grotesk (headings/display), Inter (body/UI), JetBrains Mono (scoreboard digits, countdown timers). Load via Google Fonts `<link>` as the reference does, simplest path to pixel parity. If you'd rather self-host, swap in `laravel-vite-plugin`'s `bunny()` helper for these three families instead of the unused Instrument Sans.

**Signature components to replicate as shared partials/classes** (full CSS is in `admin-dashboard.html`'s `<style>` block, copy it verbatim into `app.css` as the baseline, then diff other pages for any additions):
- `.hero` + `.pitch-lines` + `.scoreboard`: homepage hero with animated stat counters (`data-count` + `IntersectionObserver`)
- `.card-feature`, `.event-card`, `.event-date`, `.badge-status-open/-soon/-full`: public cards
- `.admin-shell`, `.sidebar` (collapsible, with `.nav-section-title`, badge counts, `.sidebar-user`), `.topbar` (search box, icon buttons with `.ping` notification dot), `.stat-card`, `.panel`, `.table-modern`, `.badge-soft-green/-yellow/-red/-grey`
- `.auth-shell`, `.auth-card` with brand panel and step indicators (login/register), `.otp-input` (auto-advancing boxes), password-strength meter

**Icons:** Bootstrap Icons (`bi-*`) for everything except four Font Awesome solid icons used specifically for facility types: `fa-futbol`, `fa-person-running`, `fa-dumbbell`, `fa-users`. Keep both icon sets loaded (already wired via `app.css`).

**Shared JS behaviors** (currently duplicated inline in every reference HTML file, port once into `resources/js/app.js`):
1. Sidebar collapse (desktop) + off-canvas toggle (mobile), with backdrop click-to-close
2. Public navbar toggler icon swap (list to x) on Bootstrap collapse show/hide
3. Animated counters: any `[data-count]` element counts up when scrolled into view
4. Chart.js global defaults (font family, color, legend style), set once, before any chart is instantiated
5. OTP input auto-advance between the 6 boxes (verify-otp page)
6. Password-strength meter (register + reset-password pages)

---

## 3. Information Architecture / Route Map

```
Public            /                  home
                  /events            events listing
                  /booking           facility reservation
                  /contact           about + contact form

Auth              /login
                  /register
                  /forgot-password
                  /reset-password/{token}
                  /verify-otp
                  POST /logout

Admin (auth+role) /admin                  dashboard
                  /admin/bookings
                  /admin/facilities
                  /admin/events
                  /admin/teams
                  /admin/staff
                  /admin/reports
                  /admin/settings

Errors            403 / 404 / 500   (override resources/views/errors/*.blade.php)
```

Admin sidebar nav is grouped into three sections (identical across all 8 admin pages): **MAIN** (Dashboard, Bookings, Events & Fixtures), **FACILITIES** (Facilities, Teams & Players, Staff & Roster), **INSIGHTS** (Reports & Analytics, Settings).

---

## 4. Database Schema

No domain migrations exist yet. Build in this order (respecting FKs):

**1. `users` (extend existing table)**
Add columns: `first_name`, `last_name`, `organisation` (nullable), `phone` (nullable), `role` (string/enum: `admin`, `facilities_manager`, `ticketing_lead`, `customer`), `access_level` (string/enum: `full`, `bookings_only`, `facilities_only`; admin-only), `avatar_url` (nullable).

**2. `otp_verifications`**
`id`, `user_id` (FK), `code` (string, 6 chars), `expires_at`, `verified_at` (nullable), timestamps.

**3. `facilities`**
`id`, `name`, `slug`, `type` (enum: `pitch`, `track`, `gym`, `hall`), `description` (text), `capacity` (int, nullable), `hourly_rate` (decimal), `status` (enum: `operational`, `maintenance`), `utilisation_percent` (int, default 0), `next_maintenance_at` (date, nullable), `icon` (string, maps to FA class), timestamps.

**4. `facility_maintenance_logs`**
`id`, `facility_id` (FK), `task`, `assigned_to`, `scheduled_at`, `status` (enum: `scheduled`, `in_progress`, `completed`), timestamps.

**5. `bookings`**
`id`, `booking_ref` (string, unique, formatted `BK-####`), `user_id` (FK, nullable, guest bookings allowed), `facility_id` (FK), `organisation` (nullable), `date`, `start_time`, `end_time`, `purpose` (enum: `league_fixture`, `training`, `community_event`, `private_function`), `expected_attendance` (int, nullable), `contact_name`, `contact_email`, `contact_phone`, `notes` (text, nullable), `amount` (decimal), `status` (enum: `pending`, `confirmed`, `cancelled`, default `pending`), timestamps.

**6. `teams`**
`id`, `name`, `category` (e.g. `Ghana Premier League`, `Youth Academy`, `Regional Athletics`), `logo_url` (nullable), `type` (enum: `resident`, `affiliate`), timestamps.

**7. `players`**
`id`, `team_id` (FK), `jersey_number` (int), `name`, `avatar_url` (nullable), `position`, `age` (int), `nationality`, `fitness_status` (enum: `fit`, `injured`, default `fit`), timestamps.

**8. `departments`** (lookup table, or just a string enum on `staff` if you'd rather skip the table; reference only shows 4: Administration, Groundskeeping, Security, Medical)
`id`, `name`.

**9. `staff`**
`id`, `user_id` (FK, nullable), `name`, `avatar_url` (nullable), `department_id` (FK) or `department` string, `role_title`, `shift` (string, e.g. "Mon-Fri, 8AM-6PM"), `matchday_duty` (string, nullable), `status` (enum: `on_shift`, `off_duty`), timestamps.

**10. `events`**
`id`, `title`, `type` (enum: `league_fixture`, `athletics`, `tournament`, `youth`, `community`), `facility_id` (FK), `date`, `start_time`, `end_time`, `capacity` (int, nullable), `expected_attendance` (int, nullable), `tickets_sold` (int, default 0), `ticket_price` (decimal, nullable), `status` (enum: `open`, `selling_fast`, `sold_out`, `confirmed`, `prep`), timestamps.

**11. `event_checklist_items`**
`id`, `event_id` (FK), `label`, `is_done` (bool, default false).

**12. `contact_messages`**
`id`, `name`, `email`, `subject` (enum: `general`, `media`, `sponsorship`, `booking_support`), `message` (text), timestamps.

**13. `notification_preferences`**
`id`, `user_id` (FK), `new_booking_requests` (bool, default true), `maintenance_reminders` (bool, default true), `weekly_summary` (bool, default false).

**Relationships:** `Facility hasMany Booking, Event, FacilityMaintenanceLog` · `Booking belongsTo Facility, User (nullable)` · `Event belongsTo Facility, hasMany EventChecklistItem` · `Team hasMany Player` · `Staff belongsTo Department (or User)` · `User hasOne NotificationPreference, hasMany OtpVerification`.

Reports/analytics (revenue by month, attendance trend, booking-purpose split, top events) are **computed from `bookings`/`events`**, not their own tables. Build them as query scopes/aggregates in the Reports page, not new models.

Scaffold each with:
```
php artisan make:model Facility -mf
php artisan make:model Booking -mf
php artisan make:model Event -mf
php artisan make:model Team -mf
php artisan make:model Player -mf
php artisan make:model Staff -mf
php artisan make:model FacilityMaintenanceLog -m
php artisan make:model EventChecklistItem -m
php artisan make:model ContactMessage -m
php artisan make:model OtpVerification -m
php artisan make:model NotificationPreference -m
php artisan make:migration add_profile_fields_to_users_table --table=users
```
Then `database/seeders/`: one seeder per entity seeded with the literal sample data visible in the reference pages (Real Tamale United, GES Athletics Unit, Main Pitch/Athletics Track/Training Annex/Conference Suite, etc.) so the admin screens look identical to the mockups out of the box. Wire them into `DatabaseSeeder::run()` and run `php artisan migrate:fresh --seed`.

---

## 5. Phased Build Plan

### Phase 0: Environment (done)
- [x] `composer install`, `.env` configured for `sqlite`, `database/database.sqlite` exists
- [x] `npm install` (Bootstrap 5, Bootstrap Icons, Font Awesome, Chart.js present in `node_modules`)
- [x] All three layouts load the Vite bundle via `@vite(...)`; `npm run build` succeeds
- Run `composer run dev` to start the Laravel server, queue, and Vite dev server together while building

### Phase 1: Database layer
1. Write all migrations from Section 4 above (exact field list, correct FKs/enums).
2. Build Eloquent models with `$fillable`, casts (dates, decimals, enums), and relationship methods.
3. Write factories + seeders using the literal sample data from the reference pages (bookings, facilities, events, teams, players, staff) so every admin screen has realistic, matching content immediately.
4. `php artisan migrate:fresh --seed` and confirm via `php artisan tinker` that relationships resolve correctly.

### Phase 2: Shared layout chrome
This is the foundation every page sits on, build it to full parity before touching individual pages.
1. Move the full design-system CSS (copy from `admin-dashboard.html`) into `resources/css/app.css`, replacing the partial/duplicated `<style>` blocks currently inline in each layout.
2. Port the shared JS behaviors (Section 2, item list) into `resources/js/app.js`.
3. Rebuild `layouts/app.blade.php` (admin shell) to match the reference exactly: collapsible sidebar with nav-section-titles, icons, badge count on Bookings, sidebar-user footer block; topbar with search box, notification/envelope icon buttons, "View site" link. Extract the sidebar nav into a `resources/views/components/admin/sidebar.blade.php` component so all 8 admin pages share one source.
4. Rebuild `layouts/main.blade.php` footer/nav to match `index.html` exactly (columns, links).
5. Rebuild `layouts/auth.blade.php` to match the split brand-panel layout used by `login.html`/`register.html` (currently a plain centered card).
6. Create `resources/views/errors/403.blade.php`, `404.blade.php`, `500.blade.php` matching `403.html`/`404.html`/`500.html`. Laravel auto-resolves these by HTTP status code, no route needed.

### Phase 3: Auth & account flows
1. `php artisan livewire:make Auth/ForgotPasswordPage`, `Auth/ResetPasswordPage`, `Auth/VerifyOtpPage`; add routes `/forgot-password`, `/reset-password/{token}`, `/verify-otp`.
2. Rebuild `LoginPage`/`RegisterPage` views to match the reference pixel-for-pixel (split panel, password visibility toggle, strength meter, SSO button, terms checkbox).
3. Add real logic: `#[Validate]` rules, `Auth::attempt()` in `LoginPage`, `User::create()` + OTP dispatch in `RegisterPage`, password-reset token flow via Laravel's built-in `Password` facade, OTP verify/resend actions in `VerifyOtpPage`.
4. Add `admin` middleware/gate so `/admin/*` routes require an authenticated user with an admin-ish role; redirect unauthorized access to the `403` view.

### Phase 4: Public pages
1. `HomePage`: scoreboard counters fed by real `Facility`/`Event` counts, 4 feature cards from `facilities` table, 3 upcoming fixtures from `events` table, CTA band, full footer.
2. `EventsPage`: category filter pills (`wire:click` setting a property, filtering a `$events` query), search input (`wire:model.live`), pagination (`WithPagination` trait).
3. `BookingPage`: full form bound with `wire:model` to a Form object or public properties (facility select, date/time, purpose, attendance, contact fields, notes), `wire:submit` creating a `Booking` record, confirmation modal, "this week's availability" sidebar computed from existing bookings per facility, policy panel (static content).
4. `ContactPage`: story/stats row (can be static or config-driven), subject select, form creating a `ContactMessage` record, map placeholder, office info panel.

### Phase 5: Admin dashboard & bookings
1. `DashboardPage`: 4 stat cards with trend arrows (compute month-over-month from `bookings`), two Chart.js charts (`revenueChart`, bar+line combo on a 6-month window; `usageChart`, doughnut of booking counts per facility), recent-bookings table (latest 4), upcoming-fixtures list (next 4 events). Pass chart data to the view as JSON and initialize charts in a small `resources/js/charts/dashboard.js` (or inline `@push('scripts')`) using Livewire's `wire:ignore` on the `<canvas>` so Livewire re-renders don't wipe the chart.
2. `BookingsPage`: status filter pills (All/Pending/Confirmed/Cancelled with live counts), search, full table with `booking_ref`, organisation+avatar, facility, date/time, purpose, amount, status badge, row actions (approve/cancel via Livewire actions), pagination.

### Phase 6: Remaining admin modules
Each follows the same pattern: `php artisan livewire:make Admin/<X>Page`, replace the `redirect('/admin')` route with the real component, build the view to match its reference file, wire to the models from Section 4.
1. **Facilities** (`admin-facilities.html`): 4 facility panels (status badge, utilisation progress bar, next maintenance date) + maintenance-log table.
2. **Events & Fixtures** (`admin-events.html`): month calendar grid with inline event badges, scheduled-events table, countdown panel (JS `setInterval` to next fixture), event-day checklist (toggle `EventChecklistItem.is_done` via Livewire), "Schedule Event" modal creating an `Event`.
3. **Teams & Players** (`admin-teams.html`): team summary cards, player roster table for the selected team (`wire:click` to switch team, re-query players).
4. **Staff & Roster** (`admin-staff.html`): 4 stat cards (total staff, on shift, departments, open positions), department filter pills, staff table.
5. **Reports & Analytics** (`admin-reports.html`): period selector, 4 YTD stat cards, 3 Chart.js charts (stacked bar revenue-by-facility, pie booking-purpose split, filled line attendance trend) computed from `bookings`/`events` aggregates, top-performing-events table, "Export PDF" (can stub with `window.print()` or a real export package later).
6. **Settings** (`admin-settings.html`): left nav-pills (Profile/Notifications/Security/Facility defaults/User roles), profile form updating the authenticated user, notification toggles bound to `notification_preferences`, user-roles table (admin-only, edits `role`/`access_level`).

### Phase 7: Authorization & business rules
1. Define a `Policy` or simple role-check middleware distinguishing `full` / `bookings_only` / `facilities_only` admin access levels; hide/disable nav items and actions accordingly.
2. Booking conflict validation: reject overlapping `date`+`start_time`/`end_time` for the same facility.
3. Status-transition rules: who can move a booking from `pending` to `confirmed`/`cancelled`.
4. Guard public booking form against past dates / facility under maintenance.

### Phase 8: Testing
Use Pest or PHPUnit (already in `composer.json`). Minimum coverage before calling a module "done":
- Feature test per auth flow (login success/failure, register, password reset, OTP verify)
- Feature test per admin CRUD module (create/update/list/filter)
- Feature test for the public booking flow, including the conflict-rejection rule
- A Dusk or manual browser pass per page against its reference screenshot to confirm visual parity

### Phase 9: Polish & deployment
1. Run `npm run build` for production assets; confirm `php artisan config:cache`/`route:cache` work.
2. Decide production DB (SQLite is fine for small deployments; switch `DB_CONNECTION` to MySQL/Postgres if expecting concurrent write load).
3. Set `APP_ENV=production`, `APP_DEBUG=false`, generate a fresh `APP_KEY` for the deployed environment.
4. Verify `storage/` and `bootstrap/cache/` are writable on the target server; run `php artisan migrate --force` on deploy.
5. Accessibility/responsive pass: the reference already includes mobile breakpoints (sidebar off-canvas, collapsed navbar). Verify every rebuilt page still works below 992px.

---

## 6. Quick Reference: Page Build Checklist

Use this as your per-page Definition of Done when implementing Phases 3 to 6. For each page: markup matches reference, data is DB-backed (no hardcoded arrays), forms have `wire:model` + validation, interactive bits work (filters, modals, charts, pagination), responsive at mobile width, matches sidebar/topbar/navbar exactly.

| Page | Route name | Livewire class |
|---|---|---|
| Home | `home` | `Public\HomePage` |
| Events | `events` | `Public\EventsPage` |
| Booking | `booking` | `Public\BookingPage` |
| Contact | `contact` | `Public\ContactPage` |
| Login | `login` | `Auth\LoginPage` |
| Register | `register` | `Auth\RegisterPage` |
| Forgot password | `password.request` | `Auth\ForgotPasswordPage` (new) |
| Reset password | `password.reset` | `Auth\ResetPasswordPage` (new) |
| Verify OTP | `otp.verify` | `Auth\VerifyOtpPage` (new) |
| Admin dashboard | `admin` | `Admin\DashboardPage` |
| Admin bookings | `admin.bookings` | `Admin\BookingsPage` |
| Admin facilities | `admin.facilities` | `Admin\FacilitiesPage` (new) |
| Admin events | `admin.events` | `Admin\EventsPage` (new) |
| Admin teams | `admin.teams` | `Admin\TeamsPage` (new) |
| Admin staff | `admin.staff` | `Admin\StaffPage` (new) |
| Admin reports | `admin.reports` | `Admin\ReportsPage` (new) |
| Admin settings | `admin.settings` | `Admin\SettingsPage` (new) |
| 403 / 404 / 500 | n/a | `resources/views/errors/{403,404,500}.blade.php` (new) |

---

## 7. Notes & Gotchas

- **One CSS/JS source only.** Don't re-add CDN `<link>`/`<script>` tags for Bootstrap/Icons/Chart.js now that `@vite` is wired up, it'll cause duplicate/conflicting versions. Pull new JS libraries into `resources/js/app.js` the same way Chart.js was added.
- **Charts + Livewire don't mix well without `wire:ignore`.** Any `<canvas>` that Chart.js attaches to must sit inside a `wire:ignore` wrapper, or re-render the chart in a `livewire:navigated`/`wire:init` hook, otherwise Livewire's DOM diffing will fight the chart library.
- **Guest bookings:** `booking.html` doesn't require login, so `Booking.user_id` must be nullable and the form must collect contact details directly. Don't gate the public booking page behind auth.
- **Font Awesome is intentionally minimal.** Only 4 facility-type icons use it; everything else in the reference is Bootstrap Icons. Don't introduce FA classes elsewhere just because the library is loaded.
