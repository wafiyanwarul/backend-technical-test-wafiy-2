<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center"><a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a></p>

# Laravel Simple API Project - Technical Test


<p align="center">
  <a href="https://github.com/your-username/laravel-sanctum-api">
    <img src="https://img.shields.io/badge/build-passing-brightgreen" alt="Build Status">
  </a>
  <a href="https://www.php.net/">
    <img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP Version">
  </a>
  <a href="https://laravel.com/">
    <img src="https://img.shields.io/badge/Laravel-12.x-orange" alt="Laravel Version">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/license-MIT-green" alt="License">
  </a>
  <a href="https://github.com/your-username">
    <img src="https://img.shields.io/badge/made%20with-%E2%9D%A4-red" alt="Made with Love">
  </a>
</p>

This repository contains a Laravel 12-based API developed as part of a technical test, utilizing Laravel Sanctum for authentication. The API implements CRUD operations for divisions and employees, along with authentication and logout functionality. This README provides detailed instructions for setup, usage, and API documentation to assist reviewers in evaluating the project.

## Table of Contents
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgments](#acknowledgments)

## Features
- Authentication using Laravel Sanctum with token-based access.
- CRUD operations for `divisions` and `employees` with filtering and pagination.
- Image upload and management for employee records.
- Secure logout functionality to invalidate tokens.
- Robust error handling with appropriate HTTP status codes.
- Optimized codebase adhering to clean code principles.

## Prerequisites
- PHP >= 8.2
- Composer
- Node.js and NPM (for frontend assets, if any)
- MySQL or another supported database
- Postman or any API testing tool
- Git

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/wafiyanwarul/backend-technical-test-wafiy-2.git
cd backend-technical-test-wafiy-2
```

### 2. Install Dependencies
Install PHP dependencies using Composer:
```bash
composer install
```

### 3. Configure Environment
Copy the `.env.example` file to `.env` and configure the environment variables:
```bash
cp .env.example .env
```
Update the following in `.env`:
- `DB_DATABASE=your_database`
- `DB_USERNAME=your_username`
- `DB_PASSWORD=your_password`
- `APP_URL=http://localhost` (or your preferred URL)

Generate an application key:
```bash
php artisan key:generate
```

### 4. Set Up Database
Create a database and run migrations to set up the schema:
```bash
php artisan migrate
```

Seed the database with dummy data (divisions and employees):
```bash
php artisan db:seed
```

### 5. Configure Storage
Link the storage directory for file uploads (e.g., employee images):
```bash
php artisan storage:link
```

### 6. Start the Development Server
Run the Laravel development server:
```bash
php artisan serve
```
The API will be available at `http://localhost:8000`.

## Usage
- Use Postman or a similar tool to test the API endpoints.
- Obtain a token by logging in and include it in the `Authorization` header as `Bearer <token>` for protected routes.
- Refer to the [API Documentation](#api-documentation) for endpoint details.

## API Documentation

### Authentication
- **Login**: Authenticate and obtain a token.
- **Logout**: Invalidate the current token.

### Endpoints

#### 1. Login (`POST /api/login`)
- **Description**: Authenticate an admin and return a token.
- **Request Body** (application/json):
  ```json
  {
      "username": "string",
      "password": "string"
  }
  ```
- **Success Response** (200):
  ```json
  {
      "status": "success",
      "message": "Login successful",
      "data": {
          "token": "plain_text_token",
          "admin": {
              "id": "uuid",
              "username": "string",
              "email": "string"
          }
      }
  }
  ```
- **Error Response** (401):
  ```json
  {
      "status": "error",
      "message": "Invalid username or password",
      "data": null
  }
  ```
- **Error Response** (403):
  ```json
  {
    "status": "error",
    "message": "Already authenticated. Please log out first.",
    "data": null
  }
  ```

#### 2. Logout (`POST /api/logout`)
- **Description**: Invalidate the current authentication token.
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (200):
  ```json
  {
      "status": "success",
      "message": "Logout successful"
  }
  ```
- **Error Response** (400):
  ```json
  {
      "status": "error",
      "message": "No token provided for logout."
  }
  ```
- **Error Response** (500):
  ```json
  {
      "status": "error",
      "message": "No valid token found for logout." "/" "Failed to logout: An unexpected error occurred."
  }
  ```

#### 3. Get All Divisions (`GET /api/divisions`)
- **Description**: Retrieve all divisions with optional name filtering.
- **Query Parameters**:
  - `name`: (optional) Filter by division name.
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (200):
  ```bash
  {
      "status": "success",
      "message": "Divisions retrieved successfully",
      "data": {
          "divisions": [
              {
                  "id": "uuid",
                  "name": "string"
              }
          ]
      },
      "pagination": {
          "current_page": 1,
          "per_page": 10,
          "total": 2,
          "last_page": 1,
          "from": 1,
          "to": 2
      }
  }
  ```
- **Error Response** (401):
  ```json
  {
      "message": "Unauthenticated."
  }
  ```

#### 4. Get All Employees (`GET /api/employees`)
- **Description**: Retrieve all employees with name and division_id filtering.
- **Query Parameters**:
  - `name`: (optional) Filter by employee name.
  - `division_id`: (optional) Filter by division UUID.
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (200):
  ```json
  {
      "status": "success",
      "message": "Employees retrieved successfully",
      "data": {
          "employees": [
              {
                  "id": "uuid",
                  "image": "image_path", // or can be null,
                  "name": "string",
                  "phone": "string",
                  "division": {
                      "id": "uuid",
                      "name": "string"
                  },
                  "position": "string"
              }
          ]
      },
      "pagination": {
          "current_page": 1,
          "per_page": 10,
          "total": 2,
          "last_page": 1,
          "from": 1,
          "to": 2
      }
  }
  ```
- **Error Response** (401):
  ```json
  {
      "message": "Unauthenticated."
  }
  ```

#### 5. Create Employee (`POST /api/employees`)
- **Description**: Create a new employee with optional image upload.
- **Request Body** (multipart/form-data):
  - `name`: required string
  - `phone`: required string
  - `division`: required UUID
  - `position`: required string
  - `image`: optional file
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (201):
  ```json
  {
      "status": "success",
      "message": "Employee created successfully"
  }
  ```
- **Error Response** (422/500):
  ```json
  {
      "message": "The given data was invalid.",
      "errors": { "field": ["error message"] }
  }
  ```

#### 6. Update Employee (`PUT /api/employees/{uuid}`)
- **Description**: Update an existing employee, including optional image update/removal.
- **Request Body** (multipart/form-data):
  - `name`: required string
  - `phone`: required string
  - `division`: required UUID
  - `position`: required string
  - `image`: optional file or "null" to remove
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (200):
  ```json
  {
      "status": "success",
      "message": "Employee updated successfully"
  }
  ```
- **Error Response** (404):
  ```json
  {
      "status": "error",
      "message": "Failed to update employee: Employee not found."
  }
  ```
- **Error Response** (500):
  ```json
  {
      "status": "error",
      "message": "Failed to update employee: Database error occurred."
  }
  ```

#### 7. Delete Employee (`DELETE /api/employees/{uuid}`)
- **Description**: Delete an employee and associated image.
- **Request Headers**:
  - `Authorization: Bearer <token>`
- **Success Response** (200):
  ```json
  {
      "status": "success",
      "message": "Employee deleted successfully"
  }
  ```
- **Error Response** (404):
  ```json
  {
      "status": "error",
      "message": "Failed to delete employee: Employee not found."
  }
  ```
- **Error Response** (500):
  ```json
  {
      "status": "error",
      "message": "Failed to delete employee: Database error occurred."
  }
  ```

## Testing
- Use Postman to test all API endpoints.
- Follow the [Installation](#installation) steps to set up the environment.
- Refer to the [API Documentation](#api-documentation) for request/response examples.
- Example Postman setup:
  - Set `Authorization` header with `Bearer <token>` for protected routes.
  - Use `form-data` for endpoints with file uploads (e.g., `POST /api/employees`, `PUT /api/employees/{uuid}`).

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License
This project is licensed under the [MIT License](LICENSE).

## Acknowledgments
- Built with [Laravel](https://laravel.com/), a powerful PHP framework.
- Authentication powered by [Laravel Sanctum](https://laravel.com/docs/sanctum).
- Thanks to company and reviewer for this technical test opportunity!
