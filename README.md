# InnovateHub — Startup Incubation & Innovation Management Platform

> A web-based platform where startup founders can submit ideas, form teams, connect with mentors, track incubation progress, participate in events, and showcase their startups to investors.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Technology Stack](#technology-stack)
- [Laravel Concepts Demonstrated](#laravel-concepts-demonstrated)
- [User Roles](#user-roles)
- [Modules & Features](#modules--features)
- [Database Schema](#database-schema)
- [External API Integration](#external-api-integration)
- [JavaScript Concepts](#javascript-concepts)
- [Installation & Setup](#installation--setup)
- [Demo Accounts](#demo-accounts)
- [Project Structure](#project-structure)
- [Git Commit History](#git-commit-history)

---

## Project Overview

**InnovateHub** is a full-stack Laravel 12 web application built as a Web Programming Laboratory project. It simulates a real-world startup incubation platform supporting four distinct user roles — Founders, Mentors, Investors, and Admins — each with their own dedicated workflow.

The platform covers the complete startup lifecycle:

- A **Founder** submits a startup idea, builds a team, requests mentorship, tracks progress through milestones and tasks, showcases their startup publicly, and communicates with mentors and investors via an internal inbox.
- A **Mentor** reviews and accepts mentorship requests, monitors assigned startup progress, and messages founders directly.
- An **Investor** browses approved and showcased startups, expresses investment interest, converts investment amounts between currencies using live exchange rates, and communicates with founders.
- An **Admin** manages the entire platform — approving ideas, managing users across all roles, creating and managing events, monitoring platform activity, and controlling attendance.

---

## Technology Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.2 |
| Database | MySQL |
| Frontend Templating | Blade Templates |
| CSS Framework | Bootstrap 5 |
| ORM | Eloquent ORM |
| Authentication | Laravel Breeze |
| HTTP Client | Guzzle HTTP |
| Asset Bundling | Vite + Sass |
| Version Control | Git & GitHub |

---

## Laravel Concepts Demonstrated

| Concept | Implementation |
|---|---|
| **MVC Architecture** | Separate Controllers, Models, and Blade Views for every module |
| **Routing** | Named routes, route groups, prefix routing per role |
| **Middleware** | `RoleMiddleware` (role-based access), `PreventBackHistory` (no-cache) |
| **Eloquent ORM** | Models with `hasOne`, `hasMany`, `belongsTo`, `belongsToMany`, scopes, accessors |
| **Eloquent Relationships** | 14 related tables with foreign key constraints and eager loading |
| **Form Request Validation** | Dedicated `FormRequest` classes for every form across all modules |
| **Authentication** | Laravel Breeze with custom role selector on registration |
| **Authorization** | Manual `abort_if()` ownership checks and role guards throughout controllers |
| **File Uploads** | Avatar, pitch deck, event banners, showcase gallery images via `Storage::disk('public')` |
| **Pagination** | `paginate()` with `withQueryString()` on all listing pages |
| **Query Builder** | Aggregate stats on admin dashboard, filtered searches with `like`, `where`, `count` |
| **Sessions** | Flash messages (`with('success', ...)`) for all CRUD feedback |
| **Cookies** | Remember-me checkbox on login — demonstrates `remember_token` cookie lifecycle |
| **CRUD Operations** | Full Create/Read/Update/Delete across Startup Ideas, Teams, Milestones, Tasks, Events |
| **Seeders & Factories** | `UserSeeder` with realistic named demo accounts for all four roles |

---

## User Roles

### Founder
- Register and manage profile
- Submit startup ideas (with optional pitch deck upload)
- Create and manage team — add/remove members by email, assign roles
- Request mentorship from available mentors (on approved ideas only)
- Track incubation progress — create milestones, add tasks, update task status
- Manage startup showcase — tagline, achievements, gallery images, website
- View and respond to investor interest expressions
- Register for platform events
- Message mentors and investors via internal inbox

### Mentor
- Register and manage profile (with expertise field)
- View incoming mentorship requests
- Accept or reject requests with optional reason
- View assigned startups and their milestone progress
- Message founders directly
- Register for platform events

### Investor
- Register and manage profile (with company and investment focus)
- Browse all approved and publicly showcased startups
- Filter startups by category and live search by keyword (AJAX)
- Convert investment amounts to 8 currencies via live exchange rates
- Express investment interest with an optional message
- Track and manage expressed interests
- Message startup founders directly
- Register for platform events

### Admin
- View aggregate platform dashboard (users, ideas, events, mentorships)
- Approve or reject startup ideas with optional rejection reason
- Manage all users — search, filter by role/status, activate/deactivate accounts, change roles
- Create, edit, and delete platform events with banner images
- Mark event attendance per registrant
- Monitor recent platform activity

---

## Modules & Features

### Module 1 — Authentication & Role Management
- Registration with role-selector card UI (Founder / Mentor / Investor)
- Login with remember-me cookie support
- Role-based redirect on login (`/founder/dashboard`, `/mentor/dashboard`, etc.)
- `RoleMiddleware` protecting all role-prefixed route groups
- Profile management with avatar upload and live preview
- Account deletion with password confirmation
- `PreventBackHistory` middleware preventing browser back-button access after logout

### Module 2 — Startup Idea Management
- Founders submit ideas with title, description, category, and optional pitch deck (PDF/PPT/DOC)
- Admin approval workflow: `pending → approved / rejected`
- Rejected ideas show reason to founder; editing a rejected idea resubmits it as pending
- Approved ideas cannot be edited
- Paginated idea listing with status badges

### Module 3 — Team Management
- Founders create one team per idea (after idea is created)
- Founder is auto-added as team member with "Founder" role
- Add members by email address; prevent duplicate membership
- Assign custom role-in-team labels (e.g. "Backend Dev", "UI Designer")
- Remove members (founder cannot remove themselves)

### Module 4 — Mentorship Management
- Founders browse available mentors with expertise and bio
- Send mentorship request with optional message (approved ideas only)
- One request per idea-mentor pair (enforced at DB level via unique constraint)
- Mentors view all incoming requests in a table; accept or reject inline
- Accepted mentors see assigned startups with milestone overview
- "Message Founder" button on assigned startup cards

### Module 5 — Progress Tracking
- Founders add milestones to approved ideas (title, description, due date)
- Add tasks to milestones (title, assignee from team, due date)
- Task status updated via inline dropdown: `todo → in_progress → done`
- Milestone status auto-syncs based on task completion
- Progress bar per milestone showing percentage of done tasks

### Module 6 — Event Management
- Admins create events with title, description, location, date/time, max attendees, banner image
- All authenticated users browse and filter events by status or search by title
- Register/unregister for events; shows "full" when max capacity is reached
- Admin event detail shows registrant list with attendance toggling (Mark Present / Mark Absent)

### Module 7 — Startup Showcase
- Founders set up a public showcase for approved ideas: tagline, achievements, website, gallery (up to 6 images)
- Toggle public visibility
- Delete individual gallery images
- Investors see only approved + public showcases when browsing
- Founder views incoming investor interests with status management (Pending / Contacted / Declined)

### Module 8 — Investor Module
- Investor dashboard with interest count and inbox link
- Browse page with AJAX live search (no page reload) and category filter
- Currency converter widget using live exchange rates from Frankfurter API
- Startup detail page: description, achievements, gallery, team, interest form, message founder button
- My Interests page with status tracking and interest withdrawal

### Module 9 — Internal Messaging System
- 1-to-1 inbox-style messaging between any two users
- Conversation auto-created or resumed via `Conversation::between()` (always lower user ID first — prevents duplicate pairs)
- Message thread with chat bubble UI (mine right/blue, theirs left/grey)
- Read receipts: `✓` sent, `✓✓` read (via `read_at` timestamp)
- Unread badge on navbar, auto-polled every 30 seconds via AJAX
- AJAX message send — optimistic UI (bubble appears immediately, fades out on failure)
- Typing indicator shown while composing a message

---

## Database Schema

### Tables (14 total)

| Table | Description |
|---|---|
| `users` | All users across all roles; includes `role`, `is_active`, profile fields |
| `investor_profiles` | Extended profile for investors (company, focus, range) |
| `startup_ideas` | Founder-submitted ideas with approval status and pitch file |
| `teams` | One team per startup idea |
| `team_members` | Pivot: team ↔ users, with `role_in_team` |
| `mentorship_requests` | Founder requests to specific mentors with status |
| `milestones` | Progress milestones per startup idea |
| `tasks` | Tasks per milestone with assignee and status |
| `events` | Platform events created by admin |
| `event_registrations` | Pivot: event ↔ users, with `attended` boolean |
| `startup_showcases` | Public showcase profile per approved idea |
| `investment_interests` | Investor interest expressions per startup idea |
| `conversations` | 1-to-1 conversation pairs (unique constraint on user pair) |
| `messages` | Messages per conversation with `read_at` timestamp |

### Key Relationships

```
User (founder)    ──< StartupIdea ──< Milestone ──< Task
StartupIdea       ──< Team        ──< TeamMember
StartupIdea       ──< MentorshipRequest
StartupIdea       ──| StartupShowcase
StartupIdea       ──< InvestmentInterest
User (mentor)     ──< MentorshipRequest
User (investor)   ──< InvestmentInterest
User              ──< Conversation ──< Message
Event             ──< EventRegistration
```

---

## External API Integration

### Frankfurter API (via Guzzle)

**Endpoint:** `https://api.frankfurter.dev/v1/latest`

**Service:** `app/Services/CurrencyService.php`

The `CurrencyService` class uses the Guzzle HTTP client to fetch live currency exchange rates from the Frankfurter open API. It is registered as a singleton in `AppServiceProvider` and injected into `CurrencyController` via Laravel's service container.

**Features:**
- Fetches real-time rates for 8 currencies: EUR, GBP, SGD, JPY, INR, AUD, CAD, CHF
- Results cached for 6 hours using Laravel's Cache facade to reduce unnecessary API calls
- Graceful fallback to hardcoded rates if the API is unreachable
- Exposed via two internal JSON endpoints:
  - `GET /api/currency/rates` — returns all current rates
  - `GET /api/currency/convert?amount=X&to=EUR` — converts a USD amount

**Usage context:** Investor browse page — investors can convert USD investment amounts to local currencies before expressing interest.

---

## JavaScript Concepts

All JavaScript is in `resources/js/app.js`. Each concept is clearly separated into named functions.

### `setTimeout`
Used in three places:
- **Alert auto-dismiss** (`initAlertAutoDismiss`) — success alerts automatically fade and remove after 4 seconds
- **Search debouncing** (`initLiveSearch`) — delays AJAX search call by 400ms after the user stops typing, preventing excessive requests
- **Typing indicator** (`initTypingIndicator`) — hides the "Typing..." indicator after 1.5 seconds of keyboard inactivity

### `setInterval`
Used in **unread badge polling** (`initUnreadBadgePolling`) — calls `GET /api/messages/unread-count` every 30 seconds and updates the navbar badge count without any page reload.

### `Promise` (explicit constructor)
`fetchJson(url)` wraps the native `fetch` API in a `new Promise((resolve, reject) => {...})` — demonstrating the Promise constructor, explicit resolve/reject flow, and error propagation. All AJAX functions in the file use `fetchJson` as their base.

### `async/await`
Every AJAX operation is written as an `async` function using `await`:
- `refreshUnreadBadge()` — awaits the unread count API
- `performSearch()` — awaits the startup search API
- `convertCurrency()` — awaits the currency conversion API
- `initAjaxMessageSend()` — awaits the message POST

### AJAX (`fetch` API)
- **Live startup search** — `GET /api/startups/search?q=...&category=...` populates the investor browse grid without page reload
- **Currency conversion** — `GET /api/currency/convert?amount=...&to=...` updates the result display inline
- **Message send** — `POST /messages/{conversation}/send` with JSON body and CSRF token; uses optimistic UI (bubble appears before server confirms)
- **Unread count** — `GET /api/messages/unread-count` polled via `setInterval`

### Event Loop
The `DOMContentLoaded` callback block in `app.js` includes inline comments explaining the JavaScript event loop — how `setTimeout`/`setInterval` callbacks are queued as macrotasks, and how resolved Promise callbacks are queued as microtasks (running before the next macrotask).

---

## Installation & Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8.0+

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/yourusername/innovatehub.git
cd innovatehub
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node dependencies**
```bash
npm install
```

**4. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME=InnovateHub
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=innovatehub
DB_USERNAME=root
DB_PASSWORD=your_password
```

**5. Create the database**
```bash
mysql -u root -p -e "CREATE DATABASE innovatehub;"
```

**6. Run migrations and seed demo data**
```bash
php artisan migrate:fresh --seed
```

**7. Create storage symlink**
```bash
php artisan storage:link
```

**8. Build frontend assets**
```bash
npm run build
```

**9. Start the development server**
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` and log in with any demo account below.

---

## Demo Accounts

All accounts use the password: `password`

| Role | Name | Email |
|---|---|---|
| Admin | Admin User | admin@innovatehub.test |
| Founder | Sarah Ahmed | sarah@innovatehub.test |
| Founder | Rahim Chowdhury | rahim@innovatehub.test |
| Founder | Priya Das | priya@innovatehub.test |
| Mentor | Dr. Karim Hossain | karim@innovatehub.test |
| Mentor | Lisa Chen | lisa@innovatehub.test |
| Investor | Venture Capital BD | vc@innovatehub.test |
| Investor | Angel Investor | angel@innovatehub.test |

---

## Project Structure

```
innovatehub/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AttendanceController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── IdeaApprovalController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Api/
│   │   │   │   ├── CurrencyController.php
│   │   │   │   ├── MessageController.php
│   │   │   │   └── StartupSearchController.php
│   │   │   ├── Founder/
│   │   │   │   ├── IdeaController.php
│   │   │   │   ├── MentorshipController.php
│   │   │   │   ├── MilestoneController.php
│   │   │   │   ├── ShowcaseController.php
│   │   │   │   ├── TaskController.php
│   │   │   │   └── TeamController.php
│   │   │   ├── Investor/
│   │   │   │   ├── InvestmentInterestController.php
│   │   │   │   └── StartupBrowserController.php
│   │   │   ├── Mentor/
│   │   │   │   └── MentorshipController.php
│   │   │   ├── EventController.php
│   │   │   ├── MessagingController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── PreventBackHistory.php
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── Admin/
│   │       │   ├── StoreEventRequest.php
│   │       │   └── UpdateEventRequest.php
│   │       ├── Founder/
│   │       │   ├── StoreMentorshipRequest.php
│   │       │   ├── StoreMilestoneRequest.php
│   │       │   ├── StoreIdeaRequest.php
│   │       │   ├── StoreShowcaseRequest.php
│   │       │   ├── StoreTaskRequest.php
│   │       │   ├── StoreTeamMemberRequest.php
│   │       │   ├── StoreTeamRequest.php
│   │       │   ├── UpdateIdeaRequest.php
│   │       │   └── UpdateTaskRequest.php
│   │       ├── Investor/
│   │       │   └── StoreInvestmentInterestRequest.php
│   │       ├── Messaging/
│   │       │   └── SendMessageRequest.php
│   │       └── UpdateProfileRequest.php
│   ├── Models/
│   │   ├── Conversation.php
│   │   ├── Event.php
│   │   ├── EventRegistration.php
│   │   ├── InvestmentInterest.php
│   │   ├── InvestorProfile.php
│   │   ├── Message.php
│   │   ├── MentorshipRequest.php
│   │   ├── Milestone.php
│   │   ├── StartupIdea.php
│   │   ├── StartupShowcase.php
│   │   ├── Task.php
│   │   ├── Team.php
│   │   ├── TeamMember.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       └── CurrencyService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php
├── resources/
│   ├── css/
│   │   └── app.scss
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── events/
│       │   ├── ideas/
│       │   └── users/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── components/
│       │   ├── milestone-status-badge.blade.php
│       │   ├── status-badge.blade.php
│       │   └── task-status-badge.blade.php
│       ├── events/
│       ├── founder/
│       │   ├── dashboard.blade.php
│       │   ├── ideas/
│       │   ├── mentorship/
│       │   ├── milestones/
│       │   ├── showcase/
│       │   └── teams/
│       ├── investor/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       ├── mentor/
│       │   ├── dashboard.blade.php
│       │   └── mentorship/
│       ├── messaging/
│       └── profile/
└── routes/
    ├── auth.php
    └── web.php
```

---

## License

This project was developed as an academic Web Programming Laboratory project.

**Course:** CSE 3100 — Web Programming Laboratory
**Stack:** Laravel 12 · MySQL · Bootstrap 5 · Blade · Eloquent ORM
