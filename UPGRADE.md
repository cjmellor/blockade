# UPGRADE

When upgrading this package, check below for any changes that are recommended for you to make.

## 1.1.1 to 1.1.2

### Add a new config option

Add this new line to your `config/blockade.php` file:

```php
/**
 * Specify the user model's foreign key.
 */
'user_foreign_key' => 'user_id',
```

### Replace Trait

In your non-User Model, swap the `CanBlock` Trait with the `HasBlocked` Trait.

**Do not do this in your User Model, that must contain `CanBlock`**

```php
use Cjmellor\Blockade\Traits\HasBlocked;

class Comment extends Model
{
    // use CanBlock; ❌
    use HasBlocked; ✅
}
```
