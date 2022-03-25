<?php


namespace BiiiiiigMonster\Fires;


use BiiiiiigMonster\Fires\Attributes\Fire;
use BiiiiiigMonster\Fires\Contracts\FiresAttributes;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

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
        $fires = $this->parse();

        $events = [];
        foreach ($fires as $key => $fire) {
            if ($this->model->isClean(explode('|', $key))) {
                continue;
            }

            /** @var Fire $fire */
            foreach ($fire->events as $event => $exact) {
                if (is_numeric($event)) {
                    $event = $exact;
                    $exact = null;
                }

                if ($this->meet($exact, $key)) {
                    $events[] = $event;
                }
            }
        }

        if (!empty($events)) {
            $this->dispatch($events);
        }
    }

    /**
     * meet fire.
     *
     * @param mixed $value
     * @param string $key
     * @return bool
     */
    protected function meet(mixed $value, string $key): bool
    {
        if (is_null($value)) {
            return true;
        }
        if ($value instanceof FiresAttributes) {
            return $value->fire($key, $this->model);
        }

        return $this->model->getAttributeValue($key) === $value;
    }

    /**
     * Parse fires of the model.
     *
     * @return array<string, Fire>
     */
    protected function parse(): array
    {
        $fires = [];

        // from fires property
        foreach ($this->model->getFires() as $key => $events) {
            $fires[$key] = new Fire($key, $events);
        }

        // from Fire Attributes
        $rfc = new ReflectionClass($this->model);
        $fireAttributes = $rfc->getAttributes(Fire::class);
        foreach ($fireAttributes as $fireAttribute) {
            /** @var Fire $fireAttributeInstance */
            $fireAttributeInstance = $fireAttribute->newInstance();
            $fires[$fireAttributeInstance->attribute] = $fireAttributeInstance;
        }

        return $fires;
    }

    /**
     * Dispatch the given event's class triggers.
     *
     * @param array $events
     */
    protected function dispatch(array $events): void
    {
        $newModel = $this->model->replicate()->syncOriginal();

        array_map(
            fn(string $event) => event(new $event($newModel)),
            $events
        );
    }
}