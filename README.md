# ✨ Luminary

> A time capsule platform where you write to your future self, lock your dreams, and share your journey to inspire others.

---

## 🌟 What is Luminary?

Luminary is where you write down your dreams, goals, and letters — lock them away — and come back to them when the time is right. Share your journey, see where others started, and realize that everyone you look up to was once exactly where you are now.

---

## 🚀 Features

- **📝 Create Capsules** — Write letters, goals, or dreams to your future self
- **🔒 Lock & Seal** — Set a deadline. Your capsule stays sealed until the date arrives
- **👁️ Visibility Control** — Choose who sees your capsule: Only You, Friends, or the World
- **👥 Group Capsules** — Set goals together with friends in a shared capsule, with member roles (owner/contributor)
- **🤝 Friend System** — Send, accept, or decline friend requests; view friends' unlocked capsules
- **🌍 Newsfeed** — A social feed of posts and inspiring capsule stories from real people's journeys
- **💬 Comments & Reactions** — React to capsules and posts (inspired / goals / proud) and leave comments
- **🔔 Notifications** — Get notified of friend requests, reactions, comments, and capsule approvals
- **🛡️ Admin Moderation** — All public submissions are reviewed before appearing in the newsfeed; admins can manage users and roles
- **📊 Dashboard** — Track your capsules, countdowns, and upcoming unlocks
- **🎨 Profile Customization** — Set an avatar, bio, and preferred font style/size

---

## 🗂️ Pages

| Page | Description |
|------|-------------|
| 🔐 Login / Register | Authentication with email verification and password reset |
| 📊 Dashboard | Summary of your capsules and upcoming unlocks |
| 💊 My Capsules | View, manage, and share your personal capsules |
| 👥 Friends | Search users, manage friend requests, view friends' capsules |
| 🌍 Newsfeed | Scroll through posts and public capsule stories |
| ➕ Create Capsule | Capsule creation flow with visibility and group options |
| 🔔 Notifications | View and mark notifications as read |
| 👤 Profile | Edit account details, avatar, bio, and font preferences |
| 🛡️ Admin Panel | Review and moderate capsules, manage users |

---

## 🔐 How Capsule Privacy Works

| Visibility | Before Deadline | After Deadline |
|------------|----------------|----------------|
| Only Me | 🔒 Only you | 🔒 Only you (unless you share) |
| Friends | 🔒 Hidden | 👥 Visible to friends |
| Public | 🔒 Hidden | 🌍 Visible after admin approval |

> **Note:** Admin can only see capsule content when a user voluntarily submits it to the newsfeed after the deadline. Locked capsules are always private.

---

## 🎨 Design System

- **Colors**: Midnight navy `#0a0f1e`, Gold accent `#c9a84c`, Teal `#4ecdc4`, Warm white `#f5f0e8`
- **Fonts**: `Playfair Display` (headings) + `DM Sans` (body)
- **Style**: Glassmorphism cards, glowing accents, smooth animations

---

## 🛠️ Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Laravel Blade templates + Alpine.js
- **Styling**: Tailwind CSS
- **Database**: MySQL
- **Build tool**: Vite
- **Auth**: Laravel's built-in authentication (Breeze-based) + custom admin guard

---

## 📦 Getting Started

```bash
# Clone the repository
git clone https://github.com/imtiaz2207015/Luminary_2207015.git

# Navigate into the project
cd Luminary_2207015

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy the environment file and set your MySQL credentials
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm run dev

# Serve the application
php artisan serve
```

---

## 📄 License

MIT License — feel free to use, modify, and share.

---

<p align="center">Made with ✨ for dreamers, builders, and future luminaries.</p>
