<?php

namespace BiiiiiigMonster\Fireable\Tests\Models;

use BiiiiiigMonster\Fireable\Concerns\HasFires;
use BiiiiiigMonster\Fireable\WhateverFireable;
use BiiiiiigMonster\Fireable\Tests\Events\AgeEighteenMatch;
use BiiiiiigMonster\Fireable\Tests\Events\AgeNineteenMatch;
use BiiiiiigMonster\Fireable\Tests\Events\UpdateAdmin;
use BiiiiiigMonster\Fireable\Tests\Events\UpdatePwd;
use BiiiiiigMonster\Fireable\Tests\Events\UpdateUsername;
use BiiiiiigMonster\Fireable\Tests\Events\UserModify;
use BiiiiiigMonster\Fireable\Tests\Fires\UserMustContactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;
    use HasFires;

    protected $fires = [
        'password' => UpdatePwd::class,
        'username' => [UserModify::class, UpdateUsername::class],
        'age' => [
            18 => AgeEighteenMatch::class,
            19 => AgeNineteenMatch::class,
            WhateverFireable::class => UserModify::class,
        ],
        'username|password' => UserModify::class,
        'status' => [UserMustContactable::class => UpdateAdmin::class],
    ];

    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }
}
