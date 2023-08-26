<?php

namespace Cjmellor\Blockade\Concerns;

use Cjmellor\Blockade\Scopes\ExcludeBlockedModelsScope;

trait HasBlocked
{
    protected static function bootHasBlocked(): void
    {
        if (auth()->hasUser()) {
            static::addGlobalScope(scope: new ExcludeBlockedModelsScope());
        }
    }
}
