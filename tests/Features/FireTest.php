<?php

use BiiiiiigMonster\Fireable\Tests\Events\AgeEighteenMatch;
use BiiiiiigMonster\Fireable\Tests\Events\AgeNineteenMatch;
use BiiiiiigMonster\Fireable\Tests\Events\UpdateAdmin;
use BiiiiiigMonster\Fireable\Tests\Events\UpdatePwd;
use BiiiiiigMonster\Fireable\Tests\Events\UpdateUsername;
use BiiiiiigMonster\Fireable\Tests\Events\UserModify;
use BiiiiiigMonster\Fireable\Tests\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

test('fire test', function () {
    Event::fake([UpdatePwd::class]);

    $user = User::inRandomOrder()->first();
    $user->password .= Str::random(3);
    $user->save();
    Event::assertDispatched(UpdatePwd::class);
});

test('not fire test', function () {
    Event::fake([UpdatePwd::class]);

    $user = User::inRandomOrder()->first();
    $user->age += 1;
    $user->save();
    Event::assertNotDispatched(UpdatePwd::class);
});

test('match fire test', function () {
    Event::fake([UpdatePwd::class, AgeEighteenMatch::class, AgeNineteenMatch::class]);

    $user = User::where('age', '!=', 18)->first();
    $user->age = 18;
    $user->save();
    Event::assertDispatched(AgeEighteenMatch::class);
    Event::assertNotDispatched(AgeNineteenMatch::class);
});

test('array fire test', function () {
    Event::fake([UpdateUsername::class, UserModify::class]);

    $user = User::inRandomOrder()->first();
    $user->username .= Str::random(3);
    $user->save();
    Event::assertDispatched(UserModify::class);
    Event::assertDispatched(UpdateUsername::class);
});

test('multi key fire test', function () {
    Event::fake([UserModify::class]);

    $user = User::inRandomOrder()->first();
    $user->password .= Str::random(3);
    $user->save();
    Event::assertDispatched(UserModify::class);
});

test('invokable fire test', function () {
    Event::fake([UpdateAdmin::class]);

    $user = User::has('phone')->where('status', '!=', 1)->first();
    $user->status = 1;
    $user->save();
    Event::assertDispatched(UpdateAdmin::class);
});

test('not invokable fire test', function () {
    Event::fake([UpdateAdmin::class]);

    $user = User::doesntHave('phone')->where('status', '!=', 1)->first();
    $user->status = 1;
    $user->save();
    Event::assertNotDispatched(UpdateAdmin::class);
});
