<?php

namespace Cjmellor\Blockade\Tests\Fixtures\Models;

use Cjmellor\Blockade\Concerns\CanBlock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CanBlock;
    use HasFactory;

    protected $table = 'users';

    protected $guarded = [];
}
