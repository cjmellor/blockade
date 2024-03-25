<?php

namespace Cjmellor\Blockade\Concerns;

use Carbon\Carbon;
use Cjmellor\Blockade\Events\UserBlocked;
use Cjmellor\Blockade\Events\UserUnblocked;
use Cjmellor\Blockade\Exceptions\CannotBlockSelfException;
use Cjmellor\Blockade\Exceptions\HasNotBlockedUserException;
use Cjmellor\Blockade\Exceptions\UserAlreadyBlockedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CanBlock
{
    protected static function bootCanBlock(): void
    {
        if (config(key: 'blockade.schedule_cleanup')) {
            config(key: 'blockade.user_model')::query()
                ->withWhereHas('blockedUsers', fn ($query) => $query->where('expires_at', '<', now()))
                ->get()
                ->each(fn ($user) => $user->blockedUsers()->detach());
        }
    }

    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            related: config(key: 'blockade.user_model'),
            table: config(key: 'blockade.blocks_table', default: 'blocks'),
            foreignPivotKey: config(key: 'blockade.blocker_foreign_key', default: 'blocker_id'),
            relatedPivotKey: config(key: 'blockade.blocked_foreign_key', default: 'blocked_id'),
        )->withPivot(columns: 'expires_at');
    }

    public function block(Model|int $user, ?Carbon $expiresAt = null): bool
    {
        throw_if(
            condition: $this->blockedUsers()->whereId($this->modelInstance($user)->id)->exists(),
            exception: UserAlreadyBlockedException::class,
        );

        throw_if(
            condition: $this->id === $this->modelInstance($user)->id,
            exception: CannotBlockSelfException::class,
        );

        $this->blockedUsers()
            ->attach(id: $this->modelInstance($user), attributes: $expiresAt !== null ? ['expires_at' => $expiresAt] : []);

        event(new UserBlocked($this->modelInstance($user)));

        return true;
    }

    protected function modelInstance(Model|int $user): Model
    {
        return $user instanceof Model ? $user : $this->findOrFail(id: $user);
    }

    public function unblock(Model|int $user): bool
    {
        throw_if(
            condition: ! $this->blockedUsers()->whereId($this->modelInstance($user)->id)->exists(),
            exception: HasNotBlockedUserException::class,
        );

        event(new UserUnblocked($this->modelInstance($user)));

        $this->blockedUsers()->detach(ids: $this->modelInstance($user));

        return true;
    }

    public function isBlocking(Model|int $user): bool
    {
        return $this->blockedUsers->contains($this->modelInstance($user)->id);
    }
}
