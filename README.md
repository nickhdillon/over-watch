# Overwatch

A lightweight project command center built for developers and small teams. Organize projects, track work, and stay focused without the complexity of traditional project management tools.

## Features

**Project Management**: Create and organize projects in a centralized workspace.

**Ticket Tracking**: Manage tasks and move work through customizable statuses.

**Tags & Organization**: Categorize work with tags to keep projects structured and easy to navigate.

**Simple & Focused**: Built to help you stay productive without unnecessary complexity.

## Installation

After cloning this repo, create a local MySQL database with the name `over-watch`, and connect to it.

Then, run the following commands from your project root:

```
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan flux:activate
npm install
npm run dev
```

Now, open the project in your browser (Typically http://over-watch.test) and use the following credentials to log in:

```
Email: admin@example.com
Password: password
```

## Testing

Run tests with:

`php artisan test`
