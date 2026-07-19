# Luminary

A personal time capsule platform — write messages, memories, or reflections today and seal them away to be unlocked at a future date. Share capsules privately, with friends, or keep them just for yourself.

## Features

- Create time capsules with a custom unlock date
- Visibility control: private (only me), friends, or group capsules
- Live countdown timers until unlock
- Friends system and social newsfeed
- Notifications for capsule activity
- Capsule review/approval workflow for shared capsules
- Customizable font style and size per user

## Tech stack

- Backend: Laravel, Laravel Breeze (authentication)
- Frontend: Blade templates, Bootstrap 5, Bootstrap Icons
- Database: MySQL
- Fonts: Google Fonts (Playfair Display, DM Sans, and others, user-selectable)

## Requirements

- PHP 8.1+
- Composer
- MySQL
- Node.js and npm

## Installation

Clone the repository:

git clone https://github.com/imtiaz2207015/luminary.git
cd luminary

Install dependencies:

composer install
npm install

Set up environment:

cp .env.example .env
php artisan key:generate

Configure your database in .env:

DB_DATABASE=luminary
DB_USERNAME=root
DB_PASSWORD=

Run migrations:

php artisan migrate

Build frontend assets:

npm run dev

Start the development server:

php artisan serve

Visit http://localhost:8000 in your browser.

## Author

Built by Imtiaz, CSE student, batch 2k22.
