<?php

namespace BiiiiiigMonster\Fires\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Fire
{
    /**
     * Fire constructor.
     *
     * @param string $field
     * @param string $event
     * @param mixed $match
     */
    public function __construct(
        public string $field,
        public string $event,
        public mixed $match
    )
    {
    }
}