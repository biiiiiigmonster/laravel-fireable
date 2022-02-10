<?php

namespace Biiiiiigmonster\Eventable\Contracts;

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
    public function match(string $key, Model $model): bool;
}