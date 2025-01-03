<?php

namespace BiiiiiigMonster\Fireable;

use BiiiiiigMonster\Fireable\Attributes\Fire;
use BiiiiiigMonster\Fireable\Contracts\InvokableFire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use ReflectionClass;

class FireManager
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
        $newModel = clone $this->model;
        $newModel->syncOriginal();

        collect($this->parse())->map(function (Fire $fire) use ($newModel) {
            if ($this->match($fire)) {
                array_map(fn($event) => event(new $event($newModel)), $fire->events);
            }
        });
    }

    /**
     * ready fire.
     *
     * @param Fire $fire
     * @return bool
     */
    protected function match(Fire $fire): bool
    {
        if (is_null($fire->match)) {
            return true;
        }

        if (is_a($fire->match, InvokableFire::class, true)) {
            $invoke = new $fire->match();
            return $invoke($fire->field, $this->model);
        }

        return $this->model->getAttributeValue($fire->field) === value($fire->match);
    }

    /**
     * Parse fires of the model.
     *
     * @return array<Fire>
     */
    protected function parse(): array
    {
        $fires = [];

        // from fires property
        foreach ($this->model->getFires() as $field => $events) {
            if ($this->model->isClean(explode('|', $field))) {
                continue;
            }

            $events = (array)$events;
            if (Arr::isList($events)) {
                $fires[] = new Fire($field, $events, null);
            } else {
                foreach ($events as $match => $event) {
                    $fires[] = new Fire($field, (array)$event, $match);
                }
            }
        }

        return $fires;
    }
}
