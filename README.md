# UniPlay — Collegiate Esports Portal

A comprehensive esports portal combining Tournament Hub, Instant Top-Up Gateway, and Match Ticketing in one ecosystem.

## Tech Stack

- **Backend:** Laravel 12.x (PHP 8.2+)
- **Database:** MySQL 8.x
- **Frontend:** Blade + Tailwind CSS v4 + Alpine.js
- **Auth:** Laravel Breeze + spatie/laravel-permission (role-based access)
- **Admin Panel:** Custom Blade admin

## Features

- **Tournament Hub** — Live/upcoming matches, standings, schedule, video shorts
- **Instant Top-Up** — Game currency top-up with WhatsApp checkout flow
- **Match Ticketing** — Physical ticket purchase with seat management and QR codes
- **Admin Panel** — Full CRUD for all entities with live score updates

## Installation

### Prerequisites

- PHP 8.2+
- MySQL 8.x
- Composer
- Node.js & npm

### Setup

```bash
# Clone the repository
git clone <repo-url>
cd uniplay

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Database Setup

1. Create a MySQL database named `uniplay`
2. Update `.env` with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=uniplay
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run migrations and seed:
   ```bash
   php artisan migrate --seed
   ```

### Build Frontend

```bash
npm run build
```

### Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@uniplay.com | password |
| User | budi@example.com | password |

## Routes

| Route | Description |
|-------|-------------|
| `/` | Home page (Arena Hub) |
| `/topup` | Top-Up product catalog |
| `/topup/{product}` | Top-Up product detail & checkout |
| `/tickets` | Match tickets listing |
| `/tickets/{match}` | Match ticket detail & purchase |
| `/schedule` | Match schedule |
| `/standings` | Tournament standings |
| `/shorts` | Video shorts & clips |
| `/search` | Search across all entities |
| `/admin` | Admin panel (admin role required) |
| `/login` | Login |
| `/register` | Register |

## Database Schema

- **users** — Authentication & roles
- **games** — Game catalog (mobile/pc/console)
- **teams** — Esports teams
- **tournaments** — Tournament definitions
- **matches** — Match fixtures with scores
- **standings** — Tournament rankings
- **venues** — Arena venues with zones
- **shorts** — Video clips
- **topup_products** — Top-up product catalog
- **topup_denominations** — Price tiers for top-up
- **topup_orders** — Top-up purchase records
- **ticket_batches** — Ticket inventory per match/zone
- **ticket_orders** — Ticket purchase records
- **banners** — Promotional banners
- **venue_faqs** — FAQ entries
- **payment_methods** — Payment method listings

## Design System

Custom "Esports Arena Dark" theme with:

- **Surface:** #0A0D14 (base), #121722 (elevated), #182030 (cards)
- **Primary:** #FF3B5C (crimson — live/urgent/CTA)
- **Secondary:** #FFB800 (gold — prestige/tickets)
- **Tertiary:** #00F0FF (cyan — verified/stats)
- **Typography:** Barlow Condensed (headings), Inter (body), JetBrains Mono (data/scores)

## License

MIT
