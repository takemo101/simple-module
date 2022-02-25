<?php

namespace Takemo101\SimpleModule\Support;

/**
 * php composer package class
 */
final class Package
{
    public function __construct(
        private string $package,
        private bool $remove,
    ) {
        $this->package = trim($this->package);
    }

    /**
     * get package name
     *
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * is remove
     *
     * @return boolean
     */
    public function isRemove(): bool
    {
        return $this->remove;
    }
}
