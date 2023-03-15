<?php

namespace Cjmellor\Blockade\Concerns;

use Cjmellor\Blockade\Scopes\ExcludeBlockedModelsScope;

trait ExcludeBlockedModels
{
    protected static function bootExcludeBlockedModels(): void
    {
        if (auth()->hasUser()) {
            static::addGlobalScope(scope: new ExcludeBlockedModelsScope());
        }
    }
}
