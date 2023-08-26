<?php

namespace Cjmellor\Blockade\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExcludeBlockedModelsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNotIn(
            column: config(key: 'blockade.user_foreign_key'),
            values: auth()->user()
                ->blockedUsers
                ->pluck('id')
                ->toArray()
        );
    }
}
