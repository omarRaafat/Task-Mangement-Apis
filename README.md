# Task Management System API

Laravel-based API for managing tasks and comments with token authentication, caching, and queued notifications.

## Features

- Authentication (token-based register/login, Bearer tokens)
- Task CRUD and listing of own/assigned tasks
- Commenting on tasks
- Email notifications dispatched via queue on new comments
- Caching of frequently read data
- Repository pattern and request validation
- Feature tests

## Tech Stack

- Laravel 12 (PHP 8.4+)
- MySQL
- Queue: database (default)
- Cache:  Redis

## Prerequisites

- PHP 8.4+
- Composer
- Git
- Node.js 18+ (only if you plan to use Vite/dev assets; not required for API)

## Quick Start (Windows PowerShell)

```powershell
# Clone
git clone Task-Mangement-App
cd Task-Mangement-App

# Install PHP dependencies
composer install

# Copy environment and generate app key
cp .env.example .env
php artisan key:generate

# Use SQLite by default (recommended for local):
# Ensure database file exists
New-Item -ItemType File -Path .\database\database.sqlite -Force | Out-Null

# Update .env (see next section) then run migrations and seeders
php artisan migrate --seed

# Serve the API
php artisan serve

# In another terminal, start the queue worker (for email notifications)
php artisan queue:work
```

The API will be available at `http://127.0.0.1:8000`.

## Environment Configuration

Minimal `.env` for local development using SQLite and queued notifications stored in the database:

```env
APP_NAME="Task Management API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack

# Database (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Cache & Queue
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file

# Mail (use log for local so emails are written to storage/logs/laravel.log)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="no-reply@example.test"
MAIL_FROM_NAME="Task Management API"

# If you prefer Redis for cache/queue, set:
# CACHE_DRIVER=redis
# QUEUE_CONNECTION=redis
# REDIS_HOST=127.0.0.1
# REDIS_PORT=6379
```

If you prefer MySQL/PostgreSQL, set `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` accordingly and remove the SQLite settings.

## Running the Application

1. Ensure `.env` is configured and the database exists.
2. Run database migrations (and seeders if desired):
   ```bash
   php artisan migrate --seed
   ```
3. Start the development server:
   ```bash
   php artisan serve
   ```
4. In a separate terminal, start the queue worker to process notifications:
   ```bash
   php artisan queue:work
   ```

## Authentication

Use the public endpoints to obtain a token, then include it as `Authorization: Bearer <token>` for all protected routes.

- `POST /api/register` – create a user
- `POST /api/login` – obtain an access token

Example (PowerShell using `Invoke-RestMethod`):

```powershell
# Register a new user
$body = @{ name = "Test User"; email = "test@example.com"; password = "Password123!" } | ConvertTo-Json
Invoke-RestMethod -Method Post -Uri http://127.0.0.1:8000/api/register -ContentType 'application/json' -Body $body

# Login with admin credentials
$login = @{ email = "admin@example.com"; password = "Password123!" } | ConvertTo-Json
$resp = Invoke-RestMethod -Method Post -Uri http://127.0.0.1:8000/api/login -ContentType 'application/json' -Body $login
$token = $resp.access_token

# Use token for protected endpoints
$headers = @{ Authorization = "Bearer $token" }
Invoke-RestMethod -Method Get -Uri http://127.0.0.1:8000/api/tasks -Headers $headers
```

## API Overview

Protected routes (require `Authorization: Bearer <token>`):

- `GET /api/tasks` – list tasks
- `POST /api/tasks` – create task
- `GET /api/tasks/{id}` – get task
- `PUT /api/tasks/{id}` – update task (owner only)
- `DELETE /api/tasks/{id}` – delete task (owner only)
- `GET /api/my-tasks` – tasks created by the authenticated user
- `GET /api/assigned-tasks` – tasks assigned to the authenticated user

Comments nested under a task:

- `GET /api/tasks/{task}/comments` – list comments
- `POST /api/tasks/{task}/comments` – add comment (dispatches notification job)
- `PUT /api/tasks/{task}/comments/{comment}` – update own comment
- `DELETE /api/tasks/{task}/comments/{comment}` – delete own comment

Note: Route protection and structure follow `routes/api.php`. Comment listing is cached for faster retrieval; creating/updating/deleting comments clears the relevant cache entries.

## Queues & Email

- New comments dispatch a job that triggers an email notification.
- By default, jobs are stored in the database; run `php artisan queue:work` to process them.
- For local development, emails are written to the log via `MAIL_MAILER=log`.

## 🧪 **API Testing**

### **🚀 Quick Testing with Postman**

#### **📥 Import the Postman Collection:**

1. **Download the collection file:**
   ```bash
   # From the project root
   curl -o Task-Management-API.postman_collection.json https://raw.githubusercontent.com/yourusername/task-management-api/main/docs/Task-Management-API.postman_collection.json
   ```

2. **Import into Postman:**
   - Open Postman
   - Click **Import** button
   - Select the `Task-Management-API.postman_collection.json` file
   - Click **Import**

3. **Set Environment Variables:**
   - Create a new environment in Postman
   - Add variable: `baseUrl` = `http://127.0.0.1:8000/api`
   - Save the environment

4. **Test Credentials (Ready to Use):**
   - **Admin**: `admin@example.com` / `Password123!`
   - **User**: `user@example.com` / `Password123!`
   - **Manager**: `manager@example.com` / `Password123!`

#### **🎯 Testing Flow:**
1. **Register** → **Login** → **Get Token** → **Test Protected Endpoints**
2. All requests are pre-configured with proper headers and examples
3. Token is automatically saved and used for authenticated requests

---

### **🌐 Interactive Swagger UI Testing**

#### **📖 Access Swagger Documentation:**

**🔗 Visit:** `http://127.0.0.1:8000/api/docs`

#### **✨ Features:**
- **Interactive API Explorer** - Test endpoints directly in the browser
- **Real-time Request/Response** - See actual API responses
- **Authentication Support** - Built-in Bearer token authentication
- **Example Data** - Pre-filled with realistic test data
- **Try It Out** - One-click testing for all endpoints

#### **🔐 How to Test with Swagger:**
1. **Open** `http://127.0.0.1:8000/api/docs`
2. **Register** a new user using the `/api/register` endpoint
3. **Login** using `/api/login` endpoint
4. **Copy the access token** from the response
5. **Click "Authorize"** button (🔒 icon)
6. **Enter:** `Bearer YOUR_ACCESS_TOKEN`
7. **Test all protected endpoints** with the "Try it out" button

#### **📋 Test Data Examples:**
```json
// Registration
{
  "name": "Test User",
  "email": "test@example.com", 
  "password": "Password123!"
}

// Login
{
  "email": "admin@example.com",
  "password": "Password123!"
}
```

---

### **🔧 Unit Testing**

Run the test suite:

```bash
php artisan test
```

If you use SQLite for testing, Laravel will use the testing database configuration automatically. Ensure the testing database is configured in `phpunit.xml` or `.env.testing` if you customize it.

## Troubleshooting

- 500 errors after fresh setup: verify `.env` values, `APP_KEY` is set, and run `php artisan config:clear && php artisan cache:clear`.
- Database errors: ensure your chosen database exists and credentials in `.env` are correct. For SQLite, the file `database/database.sqlite` must exist.
- Jobs not processing: make sure `php artisan queue:work` is running and `QUEUE_CONNECTION` matches your configuration.
- Email not sending: for local use `MAIL_MAILER=log` or configure SMTP settings.


