<?php

use Cjmellor\Blockade\Events\UserBlocked;
use Cjmellor\Blockade\Events\UserUnblocked;
use Cjmellor\Blockade\Exceptions\CannotBlockSelfException;
use Cjmellor\Blockade\Exceptions\HasNotBlockedUserException;
use Cjmellor\Blockade\Exceptions\UserAlreadyBlockedException;
use Cjmellor\Blockade\Tests\TestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

beforeEach(closure: function (): void {
    $this->modelOne = TestModel::create();

    $this->modelTwo = TestModel::create();

    config()->set(key: 'blockade.user_model', value: TestModel::class);
});

test(description: 'a User can block another User', closure: function () {
    $this->modelOne->block($this->modelTwo);
    expect($this->modelOne->blockedUsers)->toHaveCount(count: 1);

    $this->modelTwo->block((int) $this->modelOne->id);
    expect($this->modelTwo->blockedUsers)->toHaveCount(count: 1);

    $this->assertDatabaseHas(table: 'blocks', data: [
        'blocker_id' => $this->modelOne->id,
        'blocked_id' => $this->modelTwo->id,
    ]);
});

test(description: 'a User can unblock another User', closure: function () {
    $this->modelOne->block($this->modelTwo);
    $this->modelOne->unblock($this->modelTwo);
    expect($this->modelOne->blockedUsers)->toHaveCount(count: 0);

    $this->modelTwo->block($this->modelOne);
    $this->modelTwo->unblock((int) $this->modelOne->id);
    expect($this->modelTwo->blockedUsers)->toHaveCount(count: 0);

    $this->assertDatabaseMissing(table: 'blocks', data: [
        'blocker_id' => $this->modelOne->id,
        'blocked_id' => $this->modelTwo->id,
    ]);
});

test(description: 'a User cannot block a User that they have already blocked', closure: function () {
    $this->modelOne->block($this->modelTwo);
    $this->modelOne->block($this->modelTwo);
})->throws(UserAlreadyBlockedException::class);

test(description: 'a User cannot block themselves', closure: function () {
    $this->modelOne->block($this->modelOne);
})->throws(CannotBlockSelfException::class);

test(description: 'a User cannot unblock a User that they have not blocked', closure: function () {
    $this->modelOne->unblock($this->modelTwo);
})->throws(HasNotBlockedUserException::class);

test(description: 'a User can check if they are blocking another User', closure: function () {
    $this->modelOne->block($this->modelTwo);

    expect($this->modelOne->isBlocking($this->modelTwo))->toBeTrue()
        ->and($this->modelOne->isBlocking((int) $this->modelTwo->id))->toBeTrue();
});

test(description: 'a User can add an expiry date to a block', closure: function () {
    $this->modelOne->block($this->modelTwo, expiresAt: now()->addDay());

    expect($this->modelOne->blockedUsers->first()->pivot->expires_at)->toBe(now()->addDay()->toDateTimeString());
});

it('removes the blocked user after the expiry has passed', closure: function () {
    $this->modelOne->block($this->modelTwo, expiresAt: now()->addSeconds(value: 30));

    expect($this->modelOne->blockedUsers)->toHaveCount(count: 1);

    $this->assertDatabaseHas(table: 'blocks', data: [
        'blocker_id' => $this->modelOne->id,
        'blocked_id' => $this->modelTwo->id,
    ]);

    testTime()->addSeconds(value: 35);

    TestModel::withWhereHas('blockedUsers', fn ($query) => $query->where('expires_at', '<', now()))
        ->get()
        ->each(fn ($user) => $user->blockedUsers()->detach());

    expect($this->modelOne->blockedUsers()->get())->toHaveCount(count: 0);

    $this->assertDatabaseMissing(table: 'blocks', data: [
        'blocker_id' => $this->modelOne->id,
        'blocked_id' => $this->modelTwo->id,
    ]);
});

test(description: 'the unblock:expired artisan command runs correctly', closure: function () {
    $this->artisan(command: 'unblock:expired')
        ->expectsOutputToContain(string: 'No expired users to unblock')
        ->assertExitCode(exitCode: 0);

    $this->modelOne->block($this->modelTwo, expiresAt: now()->addSeconds(value: 30));
    expect($this->modelOne->blockedUsers)->toHaveCount(count: 1);

    testTime()->addSeconds(value: 35);

    $this->artisan(command: 'unblock:expired')
        ->expectsOutputToContain(string: 'Unblocked expired users')
        ->assertExitCode(exitCode: 0);

    expect($this->modelOne->blockedUsers()->get())->toHaveCount(count: 0);
});

test(description: 'An event is fired when a User is blocked', closure: function () {
    Event::fake();

    $this->modelOne->block($this->modelTwo);

    Event::assertDispatched(UserBlocked::class, fn (UserBlocked $event): bool => $event->user->id === $this->modelTwo->id);
});

test(description: 'An event is fired when a User is unblocked', closure: function () {
    Event::fake();

    $this->modelOne->block($this->modelTwo);
    $this->modelOne->unblock($this->modelTwo);

    Event::assertDispatched(UserUnblocked::class, fn (UserUnblocked $event): bool => $event->user->id === $this->modelTwo->id);
});
