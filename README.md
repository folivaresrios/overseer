kissDev Overseer Package
===================
[![Laravel 5.3](https://img.shields.io/badge/Laravel-5.3-red.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Overseer provides a simple profile-based permissions system to Laravel's built in Auth system. Overseer provides support for the following ACL structure:

- Every user can have zero or more profiles.
- Every profile can have zero or more permissions.

Permissions are then inherited to the user through the user's assigned profiles.

The package follows the FIG standards [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md), [PSR-2] (https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md), and [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md). 
This package is not unit tested, but is planned to be covered.

Documentation
-------------
Wiki: [KissDev Overseer Wiki](https://github.com/folivaresrios/overseer/wiki)

Requirements
------------
The master branch has the following requirements:

* Laravel 5.* or greater.
* PHP 5.6.4 or greater.

How to Install?
---------------
_[Using [Composer](http://getcomposer.org/)]_

Add the plugin to your project's `composer.json` - something like this:

```javascript
{
  "require": {
    "folivaresrios/overseer": "^1.0"
  }
}
```

or through command line

```
composer require folivaresrios/overseer
```

### Service Provider and Facade
Once this operation is complete, simply add the service provider and alias to your project's `config/app.php` file and run the provided migrations against your database.
```php
'providers' => [
    //...
    KissDev\Overseer\OverseerServiceProvider::class,
    //...
];
```
```php
'aliases' => [
    // ...
    'Overseer' => KissDev\Overseer\Facades\Overseer::class,
    // ...
],
```

### Migrations
You'll need to run the provided migrations against your database. Publish the migration files using the `vendor:publish` Artisan command and run `migrate`:

```
php artisan vendor:publish
php artisan migrate
```

### Route Middleware
Add the following middleware to the $routeMiddleware array in app/Http/Kernel.php BEFORE the EncryptCookies middleware:

```php
protected $routeMiddleware = [
    //...
    'profile.overseer' => \KissDev\Overseer\Middleware\UserHasProfile::class,
    'permissions.overseer' => \KissDev\Overseer\Middleware\UserHasPermission::class,
    //...
];
```

## Reporting Issues

If you have a problem with Overseer please open an issue on [GitHub](https://github.com/folivaresrios/overseer/issues).

## Contributing

If you'd like to contribute to Overseer creating something you'd like added, send a [pull
requests](https://help.github.com/articles/using-pull-requests) or open
[issues](https://github.com/folivaresrios/overseer/issues).
