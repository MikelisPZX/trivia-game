# Laravel Trivia Game

A simple trivia game built with Laravel that tests users' knowledge of numbers using the Numbers API.

## Features

- Random number trivia questions
- Multiple choice answers
- 10 questions to win
- No repeat questions in a single game
- Animated UI with glow effects

## Installation

1. Clone the repository
2. Install dependencies:

```bash
composer install
npm install
```

3. Set up environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Create SQLite database:
```bash
touch database/database.sqlite
php artisan migrate
```

5. Start the servers:
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite server
npm run dev
```

6. Visit http://localhost:8000/trivia to play!
