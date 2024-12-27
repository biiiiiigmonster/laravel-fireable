<?php

namespace BiiiiiigMonster\Fireable;

use BiiiiiigMonster\Fireable\Contracts\InvokableFire;
use Illuminate\Database\Eloquent\Model;

class WhateverFireable implements InvokableFire
{
    /**
     * @param string $key
     * @param Model $model
     * @return bool
     */
    public function __invoke($key, $model): bool
    {
        return true;
    }
}
