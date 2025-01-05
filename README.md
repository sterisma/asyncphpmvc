# Async PHP MVC Framework
PHP MVC Framework that allows asyncronous processing and standalone.\
No more apache, nginx, xampp, wampp or any runtimes. just PHP.

## Features:
- Standalone PHP application
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

## Run Server
To run server you just need PHP and only PHP. No need Apache, nginx, or anything. Run server as follows:

>       php server.php

Server should be running at a moment. This way is recommended for Production. If you in development fase consider to use daemon.

## Auto-Restart Server (Daemon)
During development, it's very helpful if we not to stop-restart server every changes we made. It's annoying!\
We use nodemon (Javascript daemon tool) to handle this. First, make sure you have already installed node.js and nodemon. To install nodemon run this

>       npm -g install nodemon

After succesfuly installed then you can run development server as follows:

>       nodemon server.php

## Create Database for application
To create a database you must have sqlite3.exe installed on your machine. Run this command.

>       sqlite3 yourdatabase.db

## Database Migrations
Create Migrations folder inside root directory.

If needed, take a look to 'migrations.php' and 'migrations-db.php' as you need.\
These two files are configurations for doctrine/migrations to run.

### Generate Migrations
In the command line you can type below command to generate migration file.

>       .\vendor\bin\doctrine-migrations generate

New migration file will be created. This filename indicates its version of migrations.

### Do Migrations
Simple command is to use migrate command. This will execute upgrade migration as default.

>       .\vendor\bin\doctrine-migrations migrate

Another command is use migrations:execute --up / --down command. This command is more clear usage.

>       .\vendor\bin\doctrine-migrations migrations:execute --down App/Migrations/version_of_migrationfile.php

Above example is used to execute downgrade migration.\
For detail documentaions, please visit the official site
https://www.doctrine-project.org/projects/doctrine-migrations/en/3.8/index.html