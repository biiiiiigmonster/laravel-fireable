<?php


namespace BiiiiiigMonster\Fireable;


use BiiiiiigMonster\Fireable\Contracts\FireableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Fireabler
{
    /**
     * Fireabler constructor.
     *
     * @param Model $model
     */
    public function __construct(
        protected Model $model
    )
    {
    }

    /**
     * Make instance.
     *
     * @param Model $model
     * @return static
     */
    public static function make(Model $model): static
    {
        return new static($model);
    }

    /**
     * Fireable trigger.
     */
    public function trigger(): void
    {
        $trigger = array_intersect_key($this->model->getFireable(), $this->model->getDirty());

        foreach ($trigger as $key => $eventClasses) {
            $eventClasses = (array)$eventClasses;
            $events = Arr::isList($eventClasses) ? $eventClasses : array_filter(
                $eventClasses,
                static fn(string|array $eventClass, mixed $value) => $value instanceof FireableAttributes
                    ? $value->fire($key, $this->model)
                    : $this->model->getAttributeValue($key) === $value,
                ARRAY_FILTER_USE_BOTH
            );

            $this->dispatch($events);
        }
    }

    /**
     * Dispatch the given event's class triggers.
     *
     * @param array $events
     */
    protected function dispatch(array $events): void
    {
        array_map(
            static fn(string $event) => event(new $event((clone $this->model)->refresh())),
            $events
        );
    }
}