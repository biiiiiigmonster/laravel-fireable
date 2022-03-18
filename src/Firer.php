<?php


namespace BiiiiiigMonster\Fires;


use BiiiiiigMonster\Fires\Contracts\FiresAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Firer
{
    /**
     * Firer constructor.
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
    public function handle(): void
    {
        foreach ($this->model->getFires() as $key => $eventClasses) {
            if (!$this->model->isDirty(explode('|', $key))) {
                continue;
            }

            $eventClasses = (array)$eventClasses;
            $events = Arr::isList($eventClasses) ? $eventClasses : array_filter(
                $eventClasses,
                fn(string|array $eventClass, mixed $fuse) => $fuse instanceof FiresAttributes
                    ? $fuse->fire($key, $this->model)
                    : $this->model->getAttributeValue($key) === $fuse,
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
            fn(string $event) => event(new $event((clone $this->model)->syncOriginal())),
            $events
        );
    }
}