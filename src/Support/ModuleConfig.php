<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Arr;

/**
 * module config class
 */
final class ModuleConfig
{
    /**
     * constructor
     *
     * @param ModulePosition $position
     * @param string $classname
     * @param string $cachePath
     * @param string[] $denies
     * @param ModulePositionCollection $positions
     */
    public function __construct(
        private ModulePosition $position,
        private string $classname,
        private string $cachePath,
        private array $denies,
        private ModulePositionCollection $positions,
    ) {
        $this->positions->add($this->position);
    }

    /**
     * get main module position
     *
     * @return ModulePosition
     */
    public function getPosition(): ModulePosition
    {
        return $this->position;
    }

    /**
     * get main module directory
     *
     * @param string ...$directories
     * @return string
     */
    public function getPath(string ...$directories): string
    {
        return $this->position->getPath(...$directories);
    }

    /**
     * get main module namespace
     *
     * @param string ...$namespaces
     * @return string
     */
    public function getNamespace(string ...$namespaces): string
    {
        return $this->position->getNamespace(...$namespaces);
    }

    /**
     * get module provider class name
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->classname;
    }

    /**
     * get cache path
     *
     * @return string
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    /**
     * get deny module names
     *
     * @return string[]
     */
    public function getDenies(): array
    {
        return $this->denies;
    }

    /**
     * in denies
     *
     * @param string $name
     * @return boolean
     */
    public function inDenies(string $name): bool
    {
        return in_array($name, $this->denies);
    }

    /**
     * get other modules
     *
     * @return ModulePositionCollection
     */
    public function getPositions(): ModulePositionCollection
    {
        return $this->positions;
    }

    /**
     * constructor from config array
     *
     * @param mixed[] $config
     * @return self
     */
    public static function fromConfigArray(array $config): self
    {
        $modules = Arr::get($config, 'modules', Arr::get($config, 'submodule', []));
        return new self(
            new ModulePosition(
                Arr::get($config, 'directory', base_path('module')),
                Arr::get($config, 'namespace', 'Module'),
            ),
            Arr::get($config, 'classname', Arr::get($config, 'filename', 'Module')),
            Arr::get($config, 'cache', Arr::get($config, 'cache_path', 'cache/module-cache.php')),
            Arr::get($config, 'denies', Arr::get($config, 'deny', [])),
            ModulePositionCollection::fromArray(
                array_map(
                    fn ($n, $d) => new ModulePosition($d, (string)$n),
                    array_keys($modules),
                    array_values($modules),
                ),
            ),
        );
    }
}
