<?php

namespace BiiiiiigMonster\Eventable\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EventableAttributes
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