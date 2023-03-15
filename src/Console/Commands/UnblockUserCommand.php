<?php

namespace Cjmellor\Blockade\Console\Commands;

use Illuminate\Console\Command;

class UnblockUserCommand extends Command
{
    protected $signature = 'unblock:expired';

    protected $description = 'Unblock\'s a User if the expired time has passed';

    public function handle(): void
    {
        $userModel = config(key: 'blockade.user_model');

        $expiredUsers = $userModel::query()
            ->withWhereHas('blockedUsers', fn ($query) => $query->where('expires_at', '<', now()))
            ->get();

        if ($expiredUsers->isEmpty()) {
            $this->components->info(string: 'No expired users to unblock');

            return;
        }

        $expiredUsers->each(fn ($user) => $user->blockedUsers()->detach());

        $this->components->info(string: 'Unblocked expired users');
    }
}
