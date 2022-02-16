<?php

namespace BiiiiiigMonster\Fireable\Contracts;

use Illuminate\Database\Eloquent\Model;

interface FireableAttributes
{
    /**
     * Decide if the cleanable retained.
     *
     * @param string $key
     * @param Model $model
     * @return bool
     */
    public function fire(string $key, Model $model): bool;
}