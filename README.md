# 🚀 Task Management System API

A robust Laravel-based REST API for comprehensive task management with advanced features including user authentication, task assignment, commenting system, and real-time email notifications.

## ✨ Features

- **🔐 Secure Authentication** - Token-based authentication with Laravel Sanctum
- **📋 Task Management** - Complete CRUD operations for tasks with assignment capabilities
- **💬 Comment System** - Real-time commenting on tasks with user notifications
- **📧 Email Notifications** - Automated email notifications via queued jobs
- **⚡ Performance Optimized** - Redis caching for improved response times
- **🏗️ Clean Architecture** - Repository pattern with proper request validation
- **🧪 Comprehensive Testing** - Full test coverage with feature and unit tests
- **📚 API Documentation** - Interactive Swagger UI and Postman collection

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.0+
- **Cache**: Redis 6.0+
- **Queue**: Database/Redis
- **Authentication**: Laravel Sanctum
- **Documentation**: Swagger/OpenAPI 3.0

## 📋 Prerequisites

- **PHP 8.2+** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** 2.0+
- **MySQL** 8.0+ or **MariaDB** 10.3+
- **Redis** 6.0+ (optional, for caching and queues)
- **Git** for version control

## 🚀 Quick Start

### **📥 Installation**

```bash
# Clone the repository
git clone <your-repo-url> Task-Management-App
cd Task-Management-App

# Install PHP dependencies
composer install

# Copy environment file and generate application key
cp .env.example .env
php artisan key:generate
```

### **🗄️ Database Setup**

1. **Create MySQL Database:**
   ```sql
   CREATE DATABASE task_management_db;
   ```

2. **Configure Environment Variables** (see Environment Configuration section below)

3. **Run Migrations and Seeders:**
   ```bash
   php artisan migrate --seed
   ```

### **🏃‍♂️ Running the Application**

```bash
# Start the development server
php artisan serve

# In another terminal, start the queue worker (for email notifications)
php artisan queue:work
```

**🌐 API Base URL:** `http://127.0.0.1:8000`

## ⚙️ Environment Configuration

### **📝 Required Environment Variables**

Create a `.env` file in the project root with the following configuration:

```env
# Application Settings
APP_NAME="Task Management API"
APP_ENV=local
APP_KEY=base64:your-generated-key-here
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Logging
LOG_CHANNEL=stack

# Database Configuration (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management_db
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password

# Cache Configuration
CACHE_DRIVER=redis
CACHE_PREFIX=task_management

# Queue Configuration
QUEUE_CONNECTION=redis

# Session Configuration
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@taskmanagement.com"
MAIL_FROM_NAME="Task Management System"

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,127.0.0.1:8000,::1
```

### **🔧 Alternative Configuration (File-based)**

If you prefer file-based caching and database queues (no Redis required):

```env
# Cache & Queue (File-based)
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file

# Mail (Log-based for development)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@taskmanagement.com"
MAIL_FROM_NAME="Task Management System"
```

## 🏃‍♂️ Running the Application

### **📋 Pre-flight Checklist**

1. ✅ **Database Created** - Ensure MySQL database `task_management_db` exists
2. ✅ **Environment Configured** - `.env` file is properly set up
3. ✅ **Dependencies Installed** - Run `composer install`
4. ✅ **Application Key Generated** - Run `php artisan key:generate`

### **🚀 Start the Application**

```bash
# 1. Run database migrations and seeders
php artisan migrate --seed

# 2. Start the development server
php artisan serve

# 3. In another terminal, start the queue worker (for email notifications)
php artisan queue:work

# 4. Optional: Start Redis server (if using Redis for cache/queues)
redis-server
```

### **🌐 Access Points**

- **API Base URL**: `http://127.0.0.1:8000`
- **Interactive Documentation**: `http://127.0.0.1:8000/api/docs`
- **API JSON Spec**: `http://127.0.0.1:8000/api/documentation`

## 🔐 Authentication

The API uses **Laravel Sanctum** for token-based authentication. All protected routes require a Bearer token in the Authorization header.

### **🔑 Authentication Flow**

1. **Register** a new user account
2. **Login** to obtain an access token
3. **Include token** in Authorization header for protected routes
4. **Logout** to revoke the token

### **📡 Authentication Endpoints**

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/register` | Register a new user | ❌ No |
| `POST` | `/api/login` | Login and get access token | ❌ No |
| `GET` | `/api/user` | Get current user profile | ✅ Yes |
| `POST` | `/api/logout` | Logout and revoke token | ✅ Yes |

### **💻 Example Usage (PowerShell)**

```powershell
# 1. Register a new user
$registerBody = @{
    name = "Test User"
    email = "test@example.com"
    password = "Password123!"
} | ConvertTo-Json

$registerResponse = Invoke-RestMethod -Method Post -Uri "http://127.0.0.1:8000/api/register" -ContentType 'application/json' -Body $registerBody

# 2. Login with credentials
$loginBody = @{
    email = "admin@example.com"
    password = "Password123!"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Method Post -Uri "http://127.0.0.1:8000/api/login" -ContentType 'application/json' -Body $loginBody

# 3. Extract access token
$accessToken = $loginResponse.access_token

# 4. Use token for protected endpoints
$headers = @{ Authorization = "Bearer $accessToken" }
$tasks = Invoke-RestMethod -Method Get -Uri "http://127.0.0.1:8000/api/tasks" -Headers $headers
```

## 📚 API Overview

### **📋 Task Management Endpoints**

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/api/tasks` | List all tasks | ✅ Yes |
| `POST` | `/api/tasks` | Create a new task | ✅ Yes |
| `GET` | `/api/tasks/{id}` | Get specific task details | ✅ Yes |
| `PUT` | `/api/tasks/{id}` | Update task (owner only) | ✅ Yes |
| `DELETE` | `/api/tasks/{id}` | Delete task (owner only) | ✅ Yes |
| `GET` | `/api/my-tasks` | Get tasks created by current user | ✅ Yes |
| `GET` | `/api/assigned-tasks` | Get tasks assigned to current user | ✅ Yes |

### **💬 Comment Management Endpoints**

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/api/tasks/{task}/comments` | List comments for a task | ✅ Yes |
| `POST` | `/api/tasks/{task}/comments` | Add comment to task | ✅ Yes |
| `PUT` | `/api/tasks/{task}/comments/{comment}` | Update comment (owner only) | ✅ Yes |
| `DELETE` | `/api/tasks/{task}/comments/{comment}` | Delete comment (owner only) | ✅ Yes |

### **🔒 Security Features**

- **Token-based Authentication** - All protected routes require Bearer token
- **Owner-only Operations** - Users can only modify their own tasks/comments
- **Request Validation** - All inputs are validated using Form Request classes
- **Rate Limiting** - Built-in protection against abuse
- **CORS Support** - Configurable cross-origin resource sharing

### **⚡ Performance Features**

- **Redis Caching** - Comment listings are cached for faster retrieval
- **Cache Invalidation** - Automatic cache clearing on data modifications
- **Database Indexing** - Optimized database queries
- **Eager Loading** - Reduced N+1 query problems

## 📧 Queues & Email Notifications

### **🔄 Queue System**

The API uses Laravel's queue system for background job processing:

- **Comment Notifications** - Email notifications are dispatched as background jobs
- **Queue Drivers** - Supports database, Redis, or file-based queues
- **Job Processing** - Run `php artisan queue:work` to process queued jobs
- **Failed Jobs** - Failed jobs are logged and can be retried

### **📨 Email Configuration**

#### **Development (Log-based)**
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@taskmanagement.com"
MAIL_FROM_NAME="Task Management System"
```

#### **Production (SMTP)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### **🚀 Queue Commands**

```bash
# Process all queued jobs
php artisan queue:work

# Process jobs with specific connection
php artisan queue:work redis

# Process failed jobs
php artisan queue:retry all

# Clear all failed jobs
php artisan queue:flush
```

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
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/TaskTest.php

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel
```

**📋 Test Configuration:**
- Tests use a separate MySQL database (configured in `phpunit.xml`)
- Database is automatically migrated and seeded for each test
- All tests run in transactions and are rolled back after completion

## 🔧 Troubleshooting

### **🚨 Common Issues & Solutions**

#### **500 Internal Server Error**
```bash
# Clear configuration and cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate application key
php artisan key:generate
```

#### **Database Connection Issues**
- ✅ **Check MySQL Service** - Ensure MySQL server is running
- ✅ **Verify Credentials** - Check `DB_USERNAME` and `DB_PASSWORD` in `.env`
- ✅ **Database Exists** - Ensure `task_management_db` database exists
- ✅ **Test Connection** - Run `php artisan migrate:status`

#### **Queue Jobs Not Processing**
```bash
# Check queue configuration
php artisan queue:work --verbose

# Process failed jobs
php artisan queue:retry all

# Monitor queue status
php artisan queue:monitor
```

#### **Email Notifications Not Working**
- ✅ **Check Mail Configuration** - Verify SMTP settings in `.env`
- ✅ **Test Email** - Use `MAIL_MAILER=log` for development
- ✅ **Queue Worker** - Ensure `php artisan queue:work` is running

#### **Redis Connection Issues**
```bash
# Test Redis connection
redis-cli ping

# Check Redis configuration
php artisan tinker
>>> Redis::ping()
```

#### **Authentication Issues**
- ✅ **Sanctum Installed** - Run `composer require laravel/sanctum`
- ✅ **Migrations Run** - Ensure `personal_access_tokens` table exists
- ✅ **Middleware Applied** - Check `auth:sanctum` middleware on routes

### **📊 Health Check Commands**

```bash
# Check application status
php artisan about

# Verify database connection
php artisan migrate:status

# Check queue configuration
php artisan queue:work --once

# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```


