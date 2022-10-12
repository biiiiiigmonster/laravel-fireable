<?php

namespace BiiiiiigMonster\Fireable\Contracts;

use Illuminate\Database\Eloquent\Model;

interface InvokableFire
{
    /**
     * Decide if the cleanable retained.
     *
     * @param string $key
     * @param Model $model
     * @return bool
     */
    public function __invoke($key, $model);
}
