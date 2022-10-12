<?php

namespace BiiiiiigMonster\Fireable\Tests\Fires;

use BiiiiiigMonster\Fireable\Contracts\InvokableFire;
use Illuminate\Database\Eloquent\Model;

class UserMustContactable implements InvokableFire
{
    /**
     * @param string $key
     * @param Model $model
     * @return bool
     */
    public function __invoke($key, $model)
    {
        return $model->$key === 1 && $model->phone?->exists;
    }
}
