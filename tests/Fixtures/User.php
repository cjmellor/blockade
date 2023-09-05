<?php

namespace Cjmellor\Blockade\Tests\Fixtures;

use Cjmellor\Blockade\Concerns\CanBlock;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CanBlock;

    protected $table = 'users';

    protected $guarded = [];
}
