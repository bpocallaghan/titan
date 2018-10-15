# Titan

#### This version is under development.
I am busy rewriting Titan and the Laravel Starter Project to be more robust and easier to maintain.

A Laravel Website and Admin Framework for your everyday Websites build in Laravel.
This project is the core framework for [Laravel Starter](https://github.com/bpocallaghan/laravel-admin-starter).

## Installation
Update your project's `composer.json` file.

```bash
composer require bpocallaghan/titan
```

```bash
php artisan migrate
```
This will create all the tables needed.

```bash
php artisan titan:publish --files=public
```
This will copy all assets to your public directory.
 - css
 - js
 - fonts
 - sounds
 - uploads
 
 Update the config/auth.php
 'model' => \Bpocallaghan\Titan\Models\User::class,

 ```bash
php artisan titan:db:seed
php artisan titan:assets
```
This will seed the core tables to get started
 - roles
 - users
 - banners
 - pages
 - navigation_admin

## Commands
The publish commands are used to copy the files from titan to your own application for customization.
For example, you need to add or change a field in a table or update text or design in blade files.
 
```bash
php artisan titan:publish --files=app
php artisan titan:publish --files=assets
php artisan titan:publish --files=events
php artisan titan:publish --files=database
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
php artisan titan:publish --files=events
```
This will copy all `Events`, `Listeners` and `Notifications` to your application.

```bash
php artisan titan:publish --files=database
```
This will copy the `database/seeds` and `database/migrations` to your application.
This is for you to execute `php artisan migrate` and `php artisan db:seed` to create the tables and populate them. 

## TODO
Move all the core files from the starter project to this repository (page builder, banners, etc)

- test in admin starter
- Page Builder
- add titan:publish --type=banner (to copy all banner files to application)
-
- create new packages for
- *banners
- *activity
- *google analytics

* add config file

- map titan routes or publish?
- load migrations or publish? (works when published - run applications migration)
- publish seeds to add dummy data