# FoundIt

A lost and found web app built with Laravel. People post items they have lost or found, owners verify claims with a question only the real owner can answer, and both sides arrange a meetup to return the item.

## Features

**For members**
- Register, log in and manage a personal account
- Post lost or found items with category, location, date, photo and a verification question
- Browse and search all items, with filters for type, category and status, and sorting by newest or oldest
- Claim an item by answering the owner's verification question
- Owners approve or decline claims (with a reason when declining)
- Propose and arrange a meetup after a claim is approved
- Private messages between the owner and the claimant, per item
- Item status tracking: **Open**, **Claim pending**, **Meetup arranged**, **Returned**
- Contact form

**For admins**
- Admin dashboard and item management
- Restrict or unrestrict users, with a stored reason
- Activity log recording what changed and who did it
- View messages sent through the contact form

## Tech stack

- PHP and [Laravel](https://laravel.com)
- Blade templates
- Bootstrap 5 and Bootstrap Icons
- MySQL or SQLite (set in `.env`)
- Vite for assets

## Getting started

### Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- A database (MySQL, MariaDB or SQLite)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/salvamuhsin/foundit-laravel.git
cd foundit-laravel

# 2. Install dependencies
composer install
npm install

# 3. Create your environment file and app key
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database details 
(`DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

```bash
# 4. Create tables and load demo data
php artisan migrate:fresh --seed

# 5. Make uploaded images publicly accessible
php artisan storage:link

# 6. Build assets and start the app
npm run build
php artisan serve
```

Then open <http://127.0.0.1:8000>. For development with hot reload, run `npm run dev` in a second terminal instead of `npm run build`.

## Demo data

`php artisan migrate:fresh --seed` runs `DatabaseSeeder`, which calls:

- `AdminSeeder` creates the admin account
- `DemoSeeder` creates 12 members (one restricted), 30 items in every status, claims, meetups, messages, contact messages and activity log entries

Items get placeholder images generated into `storage/app/public/items/`. To use real photos, put 
`.jpg`, `.png` or `.webp` files in `database/seeders/demo-images/` before seeding and the seeder will use them instead.

> `migrate:fresh` drops all tables. Do not run it against a database that holds real data.

## Project structure

```
app/
  Models/        User, Item, Claim, Meetup, Message, ContactMessage, ActivityLog
  Http/          Controllers, middleware and form requests
config/
  foundit.php    Item categories and banner photos
database/
  factories/     One factory per model
  migrations/
  seeders/       AdminSeeder, DemoSeeder, DatabaseSeeder
resources/views/ Blade templates and components
routes/web.php
```

## How a return works

1. A member posts a **lost** or **found** item with a verification question.
2. Another member opens the item and submits a claim with their answer and details.
3. The owner reviews the claim and approves or declines it.
4. After approval, a meetup is proposed (place, time, note).
5. Once the item is handed over, the claim is marked **returned**.
