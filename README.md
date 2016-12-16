kissDev Overseer Package
===================
[![Laravel 5.3](https://img.shields.io/badge/Laravel-5.3-orange.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Overseer brings a simple profile-based permissions system to Laravel's built in Auth system. Overseer brings support for the following ACL structure:

- Every user can have zero or more profiles.
- Every profile can have zero or more permissions.

Permissions are then inherited to the user through the user's assigned profiles.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Documentation
-------------
Coming soon in wiki here: [KissDev Overseer Wiki](https://github.com/folivaresrios/Overseer/wiki)

How to Install?
---------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require folivaresrios/overseer
```

### Service Provider and Facade
Once this operation is complete, simply add the service provider and alias to your project's `config/app.php` file and run the provided migrations against your database.
```php
'providers' => [
    /...
    KissDev\Overseer\OverseerServiceProvider::class
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
protected $middleware = [
    //...
    'profile.overseer' => \KissDev\Overseer\Middleware\UserHasProfile::class,
    'permissions.overseer' => \KissDev\Overseer\Middleware\UserHasPermission::class,
    //...
];
```

Created something you'd like added? Send a pull-request or open an issue!
