# Zatec challenge

This is a Laravel project. It uses Laravel-php and MySQL database.

1. install mysql server and mysql workbench (not mandatory it's just for user interface interraction with tha database or you can use the CLI) in your PC
 
2. Clone the repo `git clone https://github.com/mugishajc/zatec-challenge-api`.
3. Copy `.env.example` file to `.env` on root folder and configure your database accordingly (root, database name and database password).
4. Install composer dependencies: `composer install`.
5. Run database migrations: `php artisan migrate`.
6. Generate an app encryption key: `php artisan key:generate`.
7. Start the development server: `php artisan serve`.
8. To run tests, do `php artisan test`
