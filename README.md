# asyncphpmvc
PHP MVC Framework that allows asyncronous processing

## Features:
- Asyncronous using ReactPHP
- Routing using Fast Route
- Database using Async Sqlite
- Migration using Doctrine/Migrations
- PHP Dotenv
- Twig template engine
- support JWT Authentication
- Validation using Respect/Validation

## Clone this repo
First step is to clone this repo
>       git clone https://github.com/sterisma/asyncphpmvc

## Installation
Installation is done using composer
>       composer install

composer will automatically generates vendor folder, and do some stuffs for you.

## Environment configuration
Create .env file inside root directory.

Just simply copy-paste content from .env.example to your new .env file then edit some values as you need.

## Database Migrations
Create Migrations folder inside root directory.

If needed, take a look to 'migrations.php' and 'migrations-db.php' as you need.

These two files are configurations for doctrine/migrations to run.
