<?php

namespace Cjmellor\Blockade\Events;

class UserBlocked
{
    public function __construct(
        public $blockedUser,
    ) {
    }
}
