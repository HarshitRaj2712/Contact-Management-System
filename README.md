# Contact Management System

A Laravel 12 application for organizing personal and professional contacts. It supports rich contact records with multiple phone numbers, email addresses, and physical addresses, plus categories, tags, favorites, soft deletes, import/export, activity tracking, and birthday reminders.

## Features

- Contact CRUD with profile photos, company, job title, birthday, notes, and favorite status.
- Multiple phone numbers, email addresses, and addresses per contact.
- Categories and tags for organizing contacts.
- Search, filter, duplicate detection, and bulk delete tools.
- Trash view with restore and permanent delete actions.
- CSV import and CSV export.
- PDF export preview.
- Activity log viewer for tracking user actions.
- Birthday reminder notification command.
- Profile management through Laravel Breeze authentication.
- English and Spanish language switching.

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL or SQLite
- Laravel Breeze for authentication
- Bootstrap 5 for UI styling
- Vite for frontend asset building

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- A database connection configured in `.env`

## Setup

Install PHP dependencies:

```bash
composer install
```

Copy the environment file and generate an application key:

```bash
copy .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then run migrations:

```bash
php artisan migrate
```

Install frontend dependencies:

```bash
npm install
```

Link storage so uploaded profile photos are publicly accessible:

```bash
php artisan storage:link
```

You can also run the bundled setup script if your environment is already configured:

```bash
composer run setup
```

## Running Locally

Start the Laravel server:

```bash
php artisan serve
```

Run the Vite dev server in a second terminal:

```bash
npm run dev
```

If you want Laravel, the queue worker, logs, and Vite together, use:

```bash
composer run dev
```

## Useful Commands

```bash
php artisan test
php artisan contacts:send-birthday-reminders
php artisan schedule:run
npm run build
```

## Project Notes

- Most application routes are protected by `auth` and `verified` middleware.
- Profile photos are stored on the public disk under `storage/app/public/contacts`.
- CSV export includes contact details, category, tags, notes, and favorite status.
- The birthday reminder command records activity logs and sends mail when a contact matches today’s date.

## License

This project is open-sourced software licensed under the MIT license.
