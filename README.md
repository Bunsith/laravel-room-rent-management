# Room Rental Management System

Admin dashboard for managing rooms, customers, rentals, collections, and journal entries. Built with Laravel 10+/PHP 8.2+, MySQL, and Bootstrap 5.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

## Default Credentials

- Admin: `admin@roomrental.test` / `password`
- Staff: `staff@roomrental.test` / `password`

## Features

- Auth (login/logout/reset password)
- Room, customer, rental, invoice, journal CRUD
- Admin/staff access control (admin can manage users)
- Document missing/expired alerts on dashboard
- Printable invoice (PDF via dompdf)

## Notes

- Update `.env` for database credentials before migrating.
- Run `composer test` for unit tests covering availability and document expiry logic.
