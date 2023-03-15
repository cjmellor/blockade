<?php

namespace Cjmellor\Blockade\Exceptions;

use Exception;

class UserAlreadyBlockedException extends Exception
{
    public function __construct(string $message = 'You have already blocked this user')
    {
        parent::__construct(message: $message);
    }
}
