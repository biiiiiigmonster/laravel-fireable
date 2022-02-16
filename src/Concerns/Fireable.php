<?php


namespace BiiiiiigMonster\Fireable\Concerns;


use BiiiiiigMonster\Fireable\Fireabler;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Fireable
 * 
 * @property array $fireable The attributes that should be event for save.
 * @package BiiiiiigMonster\Fireable\Concerns
 */
trait Fireable
{
    /**
     * Auto register fireable.
     */
    protected static function bootFireable(): void
    {
        static::saved(static fn(Model $model) => Fireabler::make($model)->trigger());
    }

    /**
     * Get fireable.
     *
     * @return array
     */
    public function getFireable(): array
    {
        return $this->fireable ?? [];
    }

    /**
     * Set the fireable attributes for the model.
     *
     * @param array $fireable
     * @return $this
     */
    public function setFireable(array $fireable): static
    {
        $this->fireable = $fireable;

        return $this;
    }

    /**
     * Make the given, typically visible, attributes fireable.
     *
     * @param array|string|null $fireables
     * @return $this
     */
    public function makeFireable(array|string|null $fireables): static
    {
        $this->fireable = array_merge(
            $this->getFireable(), is_array($fireables) ? $fireables : func_get_args()
        );

        return $this;
    }

    /**
     * Make the given, typically visible, attributes fireable if the given truth test passes.
     *
     * @param mixed $condition
     * @param array|string|null $fireables
     * @return $this
     */
    public function makeFireableIf(mixed $condition, array|string|null $fireables): static
    {
        return value($condition, $this) ? $this->makeFireable($fireables) : $this;
    }
}
