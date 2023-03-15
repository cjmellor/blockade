<?php

namespace Cjmellor\Blockade\Exceptions;

use Exception;

class CannotBlockSelfException extends Exception
{
    public function __construct(string $message = 'A user cannot block themselves')
    {
        parent::__construct(message: $message);
    }
}
