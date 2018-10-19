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
php artisan titan:publish --files=database
php artisan titan:publish --files=events
php artisan titan:publish --files=helpers
php artisan titan:publish --files=public
php artisan titan:publish --files=routes
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

## TODO
- add titan:publish --type=banner (to copy only banner files to application)
- create config file (don't load routes, etc)

**create new packages for**
- *banners
- *activity
- *google analytics
- and more