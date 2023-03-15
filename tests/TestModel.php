<?php

namespace Cjmellor\Blockade\Tests;

use Cjmellor\Blockade\Concerns\CanBlock;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use CanBlock;

    protected $guarded = [];
}
