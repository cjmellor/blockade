<?php

namespace Cjmellor\Blockade\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExcludeBlockedModelsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->hasUser()) {
            $builder->whereNotIn('user_id', auth()->user()->blockedUsers->pluck('id')->toArray());
        }
    }
}
