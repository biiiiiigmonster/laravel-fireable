<?php


namespace BiiiiiigMonster\Eventable;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Eventabler
{
    /**
     * Observe model.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Eventabler constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
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
     * Eventable handle.
     */
    public function handle(): void
    {
        $eventable = $this->model->getEventable();
        foreach ($eventable as $attribute => $eventClasses) {
            $eventClasses = (array)$eventClasses;
            $isAssoc = Arr::isAssoc($eventClasses);

            $this->dispatch(
                array_filter(
                    $eventClasses,
                    fn($eventClass, $value) => $isAssoc ? $this->isMatchValue($attribute, $value) : $this->isDirtyAttribute($attribute),
                    ARRAY_FILTER_USE_BOTH
                )
            );
        }
    }

    /**
     * Determine if the model attribute value and event value matched.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function isMatchValue(string $attribute, mixed $value): bool
    {
        return $this->model->getAttributeValue($attribute) === $value;
    }

    /**
     * Determine if the given attribute have been modified.
     *
     * @param string $attribute
     * @return bool
     */
    protected function isDirtyAttribute(string $attribute): bool
    {
        foreach (explode('&', $attribute) as $item) {
            if (!$this->model->isDirty(explode('|', $item))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Dispatch the given event's class triggers.
     *
     * @param array $eventClasses
     */
    protected function dispatch(array $eventClasses): void
    {
        array_map(fn($eventClass) => event(new $eventClass($this->model)), $eventClasses);
    }
}