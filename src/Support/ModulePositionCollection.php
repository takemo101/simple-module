<?php

namespace Takemo101\SimpleModule\Support;

/**
 * module position collection class
 */
final class ModulePositionCollection
{
    /**
     * @var ModulePosition[]
     */
    private array $collection = [];

    /**
     * constructor
     *
     * @param ModulePosition ...$positions
     */
    public function __construct(ModulePosition ...$positions)
    {
        foreach ($positions as $posiiton) {
            $this->add($posiiton);
        }
    }

    /**
     * add posiiton
     *
     * @param ModulePosition $posiiton
     * @return self
     */
    public function add(ModulePosition $posiiton): self
    {
        $this->collection[$posiiton->getNamespace()] = $posiiton;

        return $this;
    }

    /**
     * find position by namespace
     *
     * @param string $namespace
     * @return ModulePosition|null
     */
    public function findByNamespace(string $namespace): ?ModulePosition
    {
        return array_key_exists($namespace, $this->collection)
            ? $this->collection[$namespace]
            : null;
    }

    /**
     * get iterator
     *
     * @return ModulePosition[]
     */
    public function iterator(): array
    {
        return $this->collection;
    }

    /**
     * constructor from array
     *
     * @param ModulePosition[] $positions
     * @return self
     */
    public static function fromArray(array $positions): self
    {
        return new self(...$positions);
    }
}
