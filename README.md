# Vinylopia: The Marketplace for Vinyl Lovers

Vinylopia is a community-driven marketplace designed for vinyl collectors, traders, and music lovers. Inspired by Discogs, Vinylopia allows users to browse, list, and purchase vinyl records directly from one another. Whether you’re hunting for rare editions, limited releases, or simply looking to expand your collection, Vinylopia brings together a passionate community of music enthusiasts to buy, sell, and trade vinyl records.

## Status
**Project Status**: Active

## Installation Steps

### 1. Clone/Download the repository

Clone or Download as ZIP this repository to your PC.

### 2. Install PHP/SQL/Composer

Make sure to install:

1. [PHP](https://www.php.net/downloads.php)
2. SQL Database, for instance, [MySQL](https://dev.mysql.com/downloads/installer/).
3. [Composer](https://getcomposer.org/download/) for managing PHP dependencies.

### 3. Create the Database

Create an account for your MySQL DB and CREATE DATABASE vinylopiaDB.
```bash
create database vinylopiaDB;
```

### 4. Configure Environment Variables

Create and edit a ```.env``` file according to your DB. 
You can take the code from ```.env.example``` file. For example:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ToDo_Example
DB_USERNAME=root
DB_PASSWORD=1234
```

### 5. Install the Necessary Dependencies

Enter this command to install all necessary dependencies
```bash
composer install
```

### 6. Create Storage Link

To create a symbolic link from ```public/storage``` to ```storage/app/public```, where user avatar images (and other files) will be stored, run the following command:
```bash
php artisan storage:link
```

### 7. Create the avatars Folder
After creating the storage link, you may need to create the ```avatars``` folder inside the ```public/storage``` directory if it doesn't exist.
Correct path should look as follow: ```public/storage/avatars```.

### 8. Generate Application Key

Laravel requires an application key to be set. You can generate it by running:
```bash
php artisan key:generate
```
### 9. Migrate the Database

After configuring the ```.env``` file, run the following command to create the necessary tables in your DB:
```bash
php artisan migrate
php artisan migrate --seed
```

### 10. Serve the Application

Start the PHP server by running:
```bash
php artisan serve
```
This command will launch the server at ```http://127.0.0.1:8000/``` by default.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/license/MIT).
