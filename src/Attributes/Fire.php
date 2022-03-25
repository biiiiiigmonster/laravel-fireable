<?php

namespace BiiiiiigMonster\Fires\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Fire
{
    /**
     * @var array $events
     */
    public array $events;

    /**
     * Fire constructor.
     *
     * @param string $attribute
     * @param string|array $events
     */
    public function __construct(
        public string $attribute,
        string|array $events
    )
    {
        $this->events = (array)$events;
    }
}