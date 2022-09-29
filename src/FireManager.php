<?php


namespace BiiiiiigMonster\Fires;


use BiiiiiigMonster\Fires\Attributes\Fire;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionMethod;
use Throwable;

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
        $fires = $this->parse();
        $newModel = $this->model->replicate()->syncOriginal();

        foreach ($fires as $field => $fire) {
            if ($this->model->isClean(explode('|', $field))) {
                continue;
            }

            if ($this->ready($fire)) {
                event(new $fire->event($newModel));
            }
        }
    }

    /**
     * meet fire.
     *
     * @param Fire $fire
     * @return bool
     */
    protected function ready(Fire $fire): bool
    {
        if (is_null($fire->match)) {
            return true;
        }

        try {
            $rfc = new ReflectionMethod($fire->match, 'fire');
            return $rfc->invokeArgs(null, [$fire->field, $this->model]);
        }catch (Throwable) {
            return $this->model->getAttributeValue($fire->field) === value($fire->match);
        }
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
        foreach ($this->model->getFires() as $field => $events) {
            foreach ((array)$events as $event => $match) {
                if (is_numeric($event)) {
                    $event = $match;
                    $match = null;
                }
                $fires[$field] = new Fire($field, $event, $match);
            }
        }

        // from Fire Attributes
        $rfc = new ReflectionClass($this->model);
        $fireAttributes = $rfc->getAttributes(Fire::class);
        foreach ($fireAttributes as $fireAttribute) {
            $fireAttributeInstance = $fireAttribute->newInstance();
            $fires[$fireAttributeInstance->field] = $fireAttributeInstance;
        }

        return $fires;
    }
}