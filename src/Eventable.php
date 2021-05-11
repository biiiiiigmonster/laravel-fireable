<?php


namespace Biiiiiigmonster\Eventable;


use Closure;

trait Eventable
{
    /**
     * 自动注册
     */
    protected static function bootEventable(): void
    {
        static::saved(function ($model) {
            $eventable = $model->getEventable();
            foreach ($eventable as $attribute => $body) {
                if (!$model->isDirty($attribute)) continue;
                foreach ((array)$body as $value => $eventClass) {
                    if (is_numeric($value) || $model->$attribute === $value) {
                        event(new $eventClass($model));
                    }
                }
            }
        });
    }

    /**
     * Get eventable
     * @return array
     */
    public function getEventable(): array
    {
        return $this->eventable;
    }

    /**
     * Set the eventable attributes for the model.
     *
     * @param array $eventable
     * @return $this
     */
    public function setEventable(array $eventable)
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
    public function makeEventable($eventables)
    {
        $this->eventable = array_merge(
            $this->eventable, is_array($eventables) ? $eventables : func_get_args()
        );

        return $this;
    }

    /**
     * Make the given, typically visible, attributes eventable if the given truth test passes.
     *
     * @param bool|Closure $condition
     * @param array|string|null $eventables
     * @return $this
     */
    public function makeEventableIf($condition, $eventables)
    {
        $condition = $condition instanceof Closure ? $condition($this) : $condition;

        return value($condition) ? $this->makeEventable($eventables) : $this;
    }
}
