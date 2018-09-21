# Titan

#### This version is under development.
I am busy rewriting Titan and the Laravel Starter Project to be more robust and easier to maintain.

This project is the core framework for [Laravel Starter](https://github.com/bpocallaghan/laravel-admin-starter).

## Installation
Update your project's `composer.json` file.

```bash
composer require bpocallaghan/titan
```

## Commands
```bash
php artisan titan:publish --files=database
```
This will copy the `database/seeds` and `database/migrations` to your application.
This is for you to execute `php artisan migrate` and `php artisan db:seed` to create the tables and populate them. 

## TODO
Move all the core files from the starter project to this repository (page builder, banners, etc)

- Page Builder
