# Laravel API with Passport Authentication - Installation Guide

Welcome to the installation guide for setting up a Laravel API with Passport authentication. This guide will walk you through the steps required to get this project up and running.

## Prerequisites

Before you begin, make sure you have the following prerequisites installed on your system:

1. **PHP:** Laravel requires PHP 8.1 or higher. You can download it from [php.net](https://www.php.net/downloads.php).

2. **Composer:** Laravel uses Composer for package management. You can download it from [getcomposer.org](https://getcomposer.org/download/).

3. **Database:** Make sure you have a database system like MySQL installed and configured.


## Installation Steps

Follow these steps to set up your Laravel API project:

### 1. Clone the Repository
```bash
git clone https://github.com/nikunjsakariya24/product-management-apis.git
cd product-management-apis
```
### 2. Install Dependencies
Use Composer to install Laravel and its dependencies:
```bash
composer install
```

### 3. Configure Environment
Rename the .env.example file to .env and configure your database connection and other environment settings:
```bash
cp .env.example .env
```

Generate a unique application key:
```bash
php artisan key:generate
```

### 4. Set Up the Database
Create the database tables:
```bash
php artisan migrate
```

### 5. Install Passport
Install Laravel Passport to enable OAuth2 authentication:
```bash
php artisan passport:install
```

### 6. Start the Development Server
You can start the Laravel development server by running:
```bash
php artisan serve
```

## Conclusion

Congratulations! You have successfully installed a Laravel API with Passport authentication. You are now able to access current routes and are ready to start building your application and securing your routes using OAuth2 authentication.

For more detailed information and to explore advanced features, please refer to the [Laravel documentation](https://laravel.com/docs) and the [Passport documentation](https://laravel.com/docs/passport).
