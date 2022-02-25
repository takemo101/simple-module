<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * module class position class
 */
final class ModuleClassPosition
{
    /**
     * @var string
     */
    const KeySeparator = ':';

    /**
     * constructor
     *
     * @param string $name
     * @param string $classname
     * @param ModulePosition $position
     * @throws InvalidArgumentException
     */
    public function __construct(
        private string $name,
        private string $classname,
        private ModulePosition $position,
    ) {
        //
    }

    /**
     * get module position
     *
     * @return ModulePosition
     */
    public function getPosition(): ModulePosition
    {
        return $this->position;
    }

    /**
     * get module name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * get provider class name
     *
     * @return string
     */
    public function getProviderClassName(): string
    {
        return $this->getNamespace($this->classname);
    }

    /**
     * get directory
     *
     * @param string ...$paths
     * @return string
     */
    public function getPath(string ...$paths): string
    {
        return $this->position->getPath($this->name, ...$paths);
    }

    /**
     * get namespace
     *
     * @param string ...$namespaces
     * @return string
     */
    public function getNamespace(string ...$namespaces): string
    {
        return $this->position->getNamespace($this->name, ...$namespaces);
    }
}
