<?php

namespace Cjmellor\Blockade\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cjmellor\Blockade\Blockade
 */
class Blockade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Cjmellor\Blockade\Models\Block::class;
    }
}
