<?php


namespace BiiiiiigMonster\Fires\Concerns;


use BiiiiiigMonster\Fires\Firer;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasFires
 * 
 * @property array $fires The attributes that should be event for save.
 * @package BiiiiiigMonster\Fires\Concerns
 */
trait HasFires
{
    /**
     * Auto register fires.
     */
    protected static function bootHasFires(): void
    {
        static::saved(static fn(Model $model) => Firer::make($model)->handle());
    }

    /**
     * Get fires.
     *
     * @return array
     */
    public function getFires(): array
    {
        return $this->fires ?? [];
    }

    /**
     * Set the fires attributes for the model.
     *
     * @param array $fires
     * @return $this
     */
    public function setFires(array $fires): static
    {
        $this->fires = $fires;

        return $this;
    }

    /**
     * Make the given, typically visible, attributes fires.
     *
     * @param array|string|null $fires
     * @return $this
     */
    public function fire(array|string|null $fires): static
    {
        $this->fires = array_merge(
            $this->getFires(), is_array($fires) ? $fires : func_get_args()
        );

        return $this;
    }
}
