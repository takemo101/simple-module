<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * module position class
 */
final class ModulePosition
{
    /**
     * @var string
     */
    const DirectorySeparator = DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    const NamespaceSeparator = '\\';

    /**
     * @var Directory
     */
    private Directory $directory;

    /**
     * @var string
     */
    private string $namespace;

    /**
     * constructor
     *
     * @param string $directory
     * @param string $namespace
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $directory,
        string $namespace,
    ) {
        $this->directory = new Directory($directory);

        $namespace = trim(trim($namespace, self::NamespaceSeparator));
        if (!$namespace) {
            throw new InvalidArgumentException('namespace is empty');
        }

        $this->namespace = $namespace;
    }

    /**
     * get path
     *
     * @param string ...$paths
     * @return string
     */
    public function getPath(string ...$paths): string
    {
        return $this->directory->join(...$paths);
    }

    /**
     * get namespace
     *
     * @param string ...$namespaces
     * @return string
     */
    public function getNamespace(string ...$namespaces): string
    {
        return implode(self::NamespaceSeparator, [
            $this->namespace,
            ...array_filter(
                array_map(fn (string $n): string => trim($n, self::NamespaceSeparator), $namespaces)
            ),
        ]);;
    }
}
