<?php


namespace BiiiiiigMonster\Eventable\Concerns;


use BiiiiiigMonster\Eventable\Eventabler;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Eventable
 * 
 * @property array $eventable The attributes that should be event for save.
 * @package BiiiiiigMonster\Eventable\Concerns
 */
trait Eventable
{
    /**
     * Auto register eventable.
     */
    protected static function bootEventable(): void
    {
        static::saved(static fn(Model $model) => Eventabler::make($model)->trigger());
    }

    /**
     * Get eventable.
     *
     * @return array
     */
    public function getEventable(): array
    {
        return $this->eventable ?? [];
    }

    /**
     * Set the eventable attributes for the model.
     *
     * @param array $eventable
     * @return $this
     */
    public function setEventable(array $eventable): static
    {
        $this->eventable = $eventable;

        return $this;
    }

    /**
     * Make the given, typically visible, attributes eventable.
     *
     * @param array|string|null $eventables
     * @return $this
     */
    public function makeEventable(array|string|null $eventables): static
    {
        $this->eventable = array_merge(
            $this->getEventable(), is_array($eventables) ? $eventables : func_get_args()
        );

        return $this;
    }

    /**
     * Make the given, typically visible, attributes eventable if the given truth test passes.
     *
     * @param mixed $condition
     * @param array|string|null $eventables
     * @return $this
     */
    public function makeEventableIf(mixed $condition, array|string|null $eventables): static
    {
        return value($condition, $this) ? $this->makeEventable($eventables) : $this;
    }
}
