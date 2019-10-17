# Titan

A Laravel Website and Admin Framework for your everyday Websites build in Laravel.
This project is the core framework for [Laravel Starter](https://github.com/bpocallaghan/laravel-admin-starter). Please check this out for the detailed Features list and more.

[Preview project here](http://bpocallaghan.co.za)
- User: github@bpocallaghan.co.za
- Password: github

Titan is nicely packaged for you so that you only have to do the following;
- install laravel
- composer require titan package
- *create database
- *setup virtual host/ host file
- run titan:setup command
- run titan:install command
- run titan:migrate command (optional)
- *open browser

Then you have your Titan Admin Starter project with all the features ready to start your coding.
 
## Installation
Update your project's `composer.json` file.

```bash
composer require bpocallaghan/titan
```

```bash
php artisan titan:setup
```
It will do the following:
 - `php artisan titan:publish --files=website`
 - Update `app\User.php`
 - Update `routes\web.php`
 - Update `app\Http\Kernel.php`
 - Update `app\Http\Handler.php`
 - Update `config\app.php`
 
```bash
php artisan titan:install
```
It will do the following:
 - Update `.env`
 - `php artisan migrate`
 - `php artisan titan:db:seed`
 - `php artisan titan:publish --files=auth`
 
 ```bash
 php artisan titan:migrate --name=
 ```
 It will do the following:
  - Specify the 'table types' you would like to migrate
  - `blog, documents, faq, locations, news, newsletter_subscribers, photos, testimonials, shop`
 
 ```bash
 (Optional)
 Mail Layout
 php artisan vendor:publish --tag=laravel-notifications
 Mail Component
 php artisan vendor:publish --tag=laravel-mail
 ```
 It will publish the mail blade files to your project for you to edit.
 

## Installation steps in Detail
```bash
php artisan migrate
```
This will create the 'core/minimum' tables needed (users table will be altered). You can run `php artisan migrate --name=blog` to include the migration tables.

```bash
php artisan titan:db:seed
```
This will seed the core tables to get started
 - roles
 - banners
 - pages
 - navigation_admin
 
 ```bash
 php artisan titan:publish --files=website
 ```
 This will copy all `Website` related files to your application.
 - views, controllers and database seeds
 - webpack.mix.js and packages.json
 - resource/assets and public/assets (css, js, fonts, images)
 
Open `routes\web.php` and uncomment the `home` route.

Open `app\Http\Kernel.php` and add the below to the end of `$routeMiddleware` list.
```php
'role'       => \Bpocallaghan\Titan\Http\Middleware\ValidateRole::class,
'auth.admin' => \Bpocallaghan\Titan\Http\Middleware\AuthenticateAdmin::class,
```
This is to register the Admin Middlewares 
- AuthenticateAdmin - If the user logging in has the `admin` role.
- ValidateRole - Admin users can have multiple roles, filter the navigation on those roles.

## Commands
The publish commands are used to copy the files from titan to your own application for customization.
For example, you need to add or change a field in a table or update text or design in blade files.
 
```bash
php artisan titan:publish --files=app
php artisan titan:publish --files=assets
php artisan titan:publish --files=auth
php artisan titan:publish --files=banner
php artisan titan:publish --files=config
php artisan titan:publish --files=database
php artisan titan:publish --files=events
php artisan titan:publish --files=helpers
php artisan titan:publish --files=pages
php artisan titan:publish --files=public
php artisan titan:publish --files=routes
php artisan titan:publish --files=website
```

```bash
php artisan titan:publish --files=app
```
This will copy all `Models`, `Views` and `Controllers` to your application.
This will also copy all `routes` and `RouteServiceProvider` to your application.

```bash
php artisan titan:publish --files=assets
```
This will copy all `assets (css, js, fonts, images)` and `webpack.js, package.json` to your application.

```bash
php artisan titan:publish --files=auth
```
This will copy all Auth related `Models`, `Views` and `Controllers` to your application.

```bash
php artisan titan:publish --files=banner
```
This will copy all Banner related `Models`, `Views` and `Controllers` to your application.

```bash
php artisan titan:publish --files=config
```
This will copy the config file of titan to your application.
You can change the admin skin (blue, red, green, etc)

```bash
php artisan titan:publish --files=database
```
This will copy the `database/seeds` and `database/migrations` to your application.

```bash
php artisan titan:publish --files=events
```
This will copy all `Events`, `Listeners`, `Mails` and `Notifications` to your application.

```bash
php artisan titan:publish --files=helpers
```
This will copy all `Helpers`, and `HelperServiceProvider` to your application. 

```bash
php artisan titan:publish --files=public
```
This will copy all `public (compiled css, js and also fonts and images)` to your application.

```bash
php artisan titan:publish --files=routes
```
This will copy all `routes`, and `RouteServiceProvider` to your application.

```bash
php artisan titan:publish --files=website
```
This will copy all `Website` related files to your application (views, controllers, assets).

**Note: If you are not going to run `php artisan titan:publish --files=routes`, remember to add the routes to your application.**

## TODO
- install command, remove laravel installed files (public/svg)
- create config file (don't load routes, etc)

**create new packages for**
- *banners
- *activity
- *google analytics
- and more
