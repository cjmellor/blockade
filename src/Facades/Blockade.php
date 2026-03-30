<?php

namespace Cjmellor\Blockade\Facades;

use Cjmellor\Blockade\Models\Block;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cjmellor\Blockade\Blockade
 */
class Blockade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Block::class;
    }
}
