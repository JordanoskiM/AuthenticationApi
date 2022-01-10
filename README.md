## About Laravel Knowledge Assessment

Athentication Api where users can login to the system and update their profile

### Requirements 
This project is using Laravel version 8.78.1 and PHP version 8.1.1

### Prerequisites

Before you continue, ensure you have met the following requirements:

* You have installed Apache
* You have installed XAMPP or LAMP
* You have installed Laravel

### Install all the dependencies using composer
composer install

### Copy the example env file and make the required configuration changes in the .env file
cp .env.example .env

### Generate a new application key
php artisan key:generate

### Run the database migrations (Set the database connection in .env before migrating)
php artisan migrate

### Start the local development server
php artisan serve

## Emails testing
* To test the emails, you can use Mailinator for the address emailtestapp@mailinator.com 
* Change the API credentials in .env file
