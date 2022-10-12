<?php

namespace BiiiiiigMonster\Fireable\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Fire
{
    /**
     * Fire constructor.
     *
     * @param string $field
     * @param array<string> $events
     * @param mixed $match
     */
    public function __construct(
        public string $field,
        public array $events,
        public mixed $match
    ) {
    }
}
