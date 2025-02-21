# chancery.

## Introduction:

Chancery provides simple containerized infrastructure of `Laravel, Nginx, PostgreSQL and Xdebug`.

## Getting started:

## local deployment:

### 1) create `.env` file in `app` directory (copy or rename `.env-example`).
### 2) Start containers through:
#### `docker compose --env-file app/.env up -d`.
### 3) Into app container run next command:
#### `composer install`.
### 4) Run the database migration using:
#### `php artisan migrate`.
### 5) Then generate encryption keys to create secure access tokens
#### `php artisan passport:install`,
### insert password grant client in `.env`
#### PASSPORT_PASSWORD_CLIENT_ID=... ,
#### PASSPORT_PASSWORD_SECRET=... .
### 6) Create the first role for users:
#### `php artisan permission:create-role client api`, `php artisan permission:create-role admin api`.
### 7) To start the seeds, run the command:
#### `php artisan db:seed`.
### 8) To run the phpstan code analyzer, run the command:
#### `./vendor/bin/phpstan analyse --memory-limit=1G`
### 9) To generate documentation, run the command:
#### `php artisan l5-swagger:generate`.
### To view the documentation in your browser, go to:
#### `http://localhost:8080/api/documentation`
