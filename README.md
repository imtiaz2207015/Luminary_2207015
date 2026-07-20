# Luminary

A personal time capsule platform — write messages, memories, or reflections today and seal them away to be unlocked at a future date. Share capsules privately, with friends, or keep them just for yourself.

## Features

- User authentication and profile management (Laravel Breeze)
- Role-based access control via custom middleware (admin vs regular users)
- Create time capsules with title, description, unlock date, and attached files
- Capsules stay locked until their unlock date, then automatically become viewable
- Capsule membership: invite others to collaborate on a shared capsule
- Friend system: search, send, accept, or decline friend requests
- Posts, comments, and reactions within unlocked capsules
- Real-time notifications for friend requests, invitations, comments, and upcoming unlocks
- Live countdown timers and instant search, powered by Alpine.js
- Dedicated JSON API layer for capsules, posts, comments, reactions, and notifications
- Administrative dashboard for monitoring users and capsules

## Course context

Built as a project for CSE 3100 (Web Programming Laboratory), Department of Computer Science & Engineering, Khulna University of Engineering & Technology.

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
