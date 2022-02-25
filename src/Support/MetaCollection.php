<?php

namespace Takemo101\SimpleModule\Support;

use Generator;

/**
 * module meta collection class
 */
final class MetaCollection
{
    /**
     * @var Meta[]
     */
    private array $collection = [];

    /**
     * constructor
     *
     * @param Meta ...$metas
     */
    public function __construct(Meta ...$metas)
    {
        foreach ($metas as $meta) {
            $this->add($meta);
        }
    }

    /**
     * add meta
     *
     * @param Meta $meta
     * @return self
     */
    public function add(Meta $meta): self
    {
        $this->collection[$meta->name()] = $meta;

        return $this;
    }

    /**
     * find meta by name
     *
     * @param string $name
     * @return Meta|null
     */
    public function findByName(string $name): ?Meta
    {
        return array_key_exists($name, $this->collection)
            ? $this->collection[$name]
            : null;
    }

    /**
     * get iterator by name
     *
     * @param string|null $name
     * @return Meta[]
     */
    public function iteratorByName(?string $name = null): array
    {
        if ($name) {
            if ($meta = $this->findByName($name)) {
                return [$meta];
            } else {
                return [];
            }
        }

        return $this->collection;
    }

    /**
     * get iterator
     *
     * @return Meta[]
     */
    public function iterator(): array
    {
        return $this->collection;
    }

    /**
     * to meta dto array
     *
     * @return MetaDTO[]
     */
    public function toMetaDTOs(): array
    {
        $result = [];
        foreach ($this->collection as $meta) {
            $result[] = new MetaDTO(
                $meta->name(),
                $meta->directory(),
                $meta->isInstalled(),
            );
        }

        return $result;
    }

    /**
     * get meta providers
     *
     * @return string[]
     */
    public function getProviders(): array
    {
        $result = [];

        foreach ($this->collection as $meta) {
            $result[] = $meta->provider();
        }

        return $result;
    }

    /**
     * to installed collection
     *
     * @param self $collection
     * @return self
     */
    public static function toInstalledCollection(self $collection): self
    {
        $result = [];

        foreach ($collection->iterator() as $meta) {
            if ($meta->isInstalled()) {
                $result[] = $meta;
            }
        }

        return self::fromArray($result);
    }

    /**
     * to not installed collection
     *
     * @param self $collection
     * @return self
     */
    public static function toNotInstalledCollection(self $collection): self
    {
        $result = [];

        foreach ($collection->iterator() as $meta) {
            if (!$meta->isInstalled()) {
                $result[] = $meta;
            }
        }

        return self::fromArray($result);
    }

    /**
     * constructor from array
     *
     * @param Meta[] $metas
     * @return self
     */
    public static function fromArray(array $metas = []): self
    {
        return new self(...$metas);
    }
}
