# Allow a User Model to Block Another User Model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cjmellor/blockade.svg?style=flat-square)](https://packagist.org/packages/cjmellor/blockade)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cjmellor/blockade/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cjmellor/blockade/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cjmellor/blockade.svg?style=flat-square)](https://packagist.org/packages/cjmellor/blockade)

Allow a User to block another User. Blocking a User will prevent the blocker from seeing the blockees data.

## Installation

You can install the package via composer:

```bash
composer require cjmellor/blockade
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="blockade-migrations"

php artisan migrate
```

and publish the config file with:

```bash
php artisan vendor:publish --tag="blockade-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * If your User model different to the default, you can specify it here.
     */
    'user_model' => 'App\Models\User',

    /**
     * Specify the table name for the blocks table.
     */
    'blocks_table' => 'blocks',

    /**
     * Specify the foreign key for the blocker.
     */
    'blocker_foreign_key' => 'blocker_id',

    /**
     * Specify the foreign key for the blocked.
     */
    'blocked_foreign_key' => 'blocked_id',

    /**
     * Schedule the cleanup of expired blocks.
     */
    'schedule_cleanup' => false,
];

```

## Usage

### Using the Trait

Add the `Blockable` trait to your User model.

```php
use Cjmellor\Blockade\Concerns\CanBlock;

class User
{
    use CanBlock;
    
    // ...
}
```

### Block a User

A User can block another User by supplying a User model, or just the ID of the User to be blocked.

```php
$user->block(User::find(2)));

// or

$user->block(2);
```

> **Note:** 
> You cannot block yourself.

### Unblock a User

A User can unblock another User by supplying a User model, or just the ID of the User to be unblocked.

```php
$user->unblock(User::find(2)));

// or

$user->unblock(2);
```

### Checking if a User is blocked

You can run a check to see if you're already blocking a User or not.

```php
$user->isBlocking(User::find(2)));

// or

$user->isBlocking(2);
```

### Set a block expiry

You can set an expiry date when blocking a User. This will automatically unblock the User after the expiry date has passed.

```php
$user->block(User::find(2), expiresAt: now()->addDays(7)));
```

This example will block the User for 7 days. After 7 days, the User will be unblocked.

#### ⏱️ Scheduling the cleanup of expired blocks

By default, a blocked User with an expiry will not be removed after the expiry time as past.

If you want to automatically remove expired blocks, you can set the `schedule_cleanup` config value in the `config/blockade` config file to `true`.

If you want more control over when the cleanup is run, there is an artisan command that can be run to remove expired blocks.

```bash
php artisan unblock:expired
```

You can also schedule this command to run automatically.

```php
$schedule->command('unblock:expired')->everyMinute();
```

> **Note:**
> Don't forget to add the command to the `App\Console\Kernel` `schedule()` method.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
