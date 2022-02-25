<?php

namespace Takemo101\SimpleModule\Support;

/**
 * module meta dto class
 */
final class MetaDTO
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $directory
     * @param boolean $installed
     */
    public function __construct(
        private string $name,
        private string $directory,
        private bool $installed
    ) {
        //
    }

    /**
     * get module name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * return module directory path
     *
     * @return string
     */
    public function directory(): string
    {
        return $this->directory;
    }

    /**
     * is installed
     *
     * @return boolean
     */
    public function isInstalled(): bool
    {
        return $this->installed;
    }
}
