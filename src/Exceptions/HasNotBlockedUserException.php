<?php

namespace Cjmellor\Blockade\Exceptions;

use Exception;

class HasNotBlockedUserException extends Exception
{
    public function __construct(string $message = 'You have not blocked this user')
    {
        parent::__construct(message: $message);
    }
}
