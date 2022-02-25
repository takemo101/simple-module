<?php

namespace Takemo101\SimpleModule\Support;

use InvalidArgumentException;

/**
 * directory value object class
 */
final class Directory
{
    /**
     * @var string
     */
    const Separator = DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    private string $path;

    /**
     * constructor
     *
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $path,
    ) {
        $path = trim(rtrim($path, self::Separator));

        if (!$path) {
            throw new InvalidArgumentException('path is empty');
        }

        $this->path = $path;
    }

    /**
     * path join
     *
     * @param string ...$paths
     * @return string
     */
    public function join(string ...$paths): string
    {
        return implode(self::Separator, [
            $this->path,
            ...array_filter(
                array_map(fn (string $p): string => trim($p, self::Separator), $paths)
            ),
        ]);
    }

    /**
     * get path value
     *
     * @return string
     */
    public function value(): string
    {
        return $this->path;
    }
}
