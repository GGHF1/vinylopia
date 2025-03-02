# Vinylopia: The Marketplace for Vinyl Lovers

Vinylopia is a community-driven marketplace designed for vinyl collectors, traders, and music lovers. Inspired by Discogs, Vinylopia allows users to browse, list, and purchase vinyl records directly from one another. Whether you’re hunting for rare editions, limited releases, or simply looking to expand your collection, Vinylopia brings together a passionate community of music enthusiasts to buy, sell, and trade vinyl records.

## Status
**Project Status**: Active

## Installation Steps

### 1. Clone/Download the repository

Clone or Download as ZIP this repository to your PC.
[Go to Step 10: Register for Spotify Developer Account](#register-for-spotify-developer-account-and-create-an-application)

### 2. Install PHP/SQL/Composer

Make sure to install:

1. [PHP](https://www.php.net/downloads.php)
2. SQL Database, for instance, [MySQL](https://dev.mysql.com/downloads/installer/).
3. [Composer](https://getcomposer.org/download/) for managing PHP dependencies.

### 3. Create the Database

Create an account for your MySQL DB and CREATE DATABASE vinylopiaDB.
```bash
create database vinylopiaDB
```

### 4. Configure Environment Variables

#### 4.1. Database Configuration

Create and edit a ```.env``` file according to your DB and Spotify API settings.
You can take the code from ```.env.example``` file.
After creating ```.env``` file, replace DB information with yours. For example:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vinylopiaDB
DB_USERNAME=root
DB_PASSWORD=1234
```

#### 4.2. Spotify API Configuration

To integrate with the Spotify API, you'll need to create a Spotify Developer account and register your application to get your Client ID and Client Secret. Here is how you do it:
1. Create a Spotify Developer Account
   - Go to the [Spotify Developer Dashboard](https://developer.spotify.com/dashboard/applications).
   - Log in with your existing Spotify account or register a new account, if you don't have one.
   - After logging in, you will be redirected to Spotify Developer Dashboard.

2. Create a New Application
   - On the Developer Dashboard, click on the "Create App" button.
   - A form will appear where you need to provide the following details:
     1) App name;
     2) App Description;
     3) Website (optional);
     4) Redirect URI (for example: http://127.0.0.1:8000/).
     5) In a section "Which API/SDKs are you planning to use?" click on "Web API"
   - After filling out the details, click "Save" button to create the application.

3. Obtain Your Client ID and Client Secret
   - After creating the application, you will be redirected to the application page where you need to click on "Settings" button.
   - On the settings page you will see your "Client ID" and "View client secret" button, which opens "Client secret"

4. Add the following lines to your `.env` file, replacing the placeholders with your actual **Spotify Client credentials**. 
```
SPOTIFY_CLIENT_ID="your_id"
SPOTIFY_CLIENT_SECRET="your_secret"
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

After creating the storage link, you may need to create the ```avatars``` folder inside ```public/storage``` and ```storage/app/public``` directories if it doesn't exist.   

Correct paths should look as follows: ```public/storage/avatars``` and ```storage/app/public/avatars```

### 8. Generate Application Key

Laravel requires an application key to be set. You can generate it by running:
```bash
php artisan key:generate
```
### 9. Migrate the Database

After configuring the ```.env``` file, run the following commands to create the necessary tables in your database and populate them with vinyl information:
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
