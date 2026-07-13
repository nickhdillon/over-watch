# Overwatch

A lightweight project command center built for developers and small teams. Organize projects, track work, and stay focused without the complexity of traditional project management tools.

## Features

**Projects**: Organize your work across projects with a clean, centralized workspace.

**Tickets**: Track work through a simple, built-in workflow with drag-and-drop board and list views.

**Releases**: Group tickets into releases to plan milestones and track progress toward each delivery.

**Tags**: Categorize and filter work with project-specific tags.

**Simple & Focused**: Designed to help developers and small teams ship faster without unnecessary complexity.

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
