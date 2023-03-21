<?php

namespace Cjmellor\Blockade\Events;

class UserUnblocked
{
    public function __construct(
        public $user,
    ) {
    }
}
