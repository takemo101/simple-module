<?php

namespace Takemo101\SimpleModule\Support;

/**
 * module meta class
 */
final class Meta
{
    /**
     * @var string
     */
    const InstalledFilename = 'installed';

    /**
     * constructor
     *
     * @param ModuleClassPosition $classPosition
     * @param boolean $installed
     */
    public function __construct(
        private ModuleClassPosition $classPosition,
        private bool $installed,
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
        return $this->classPosition->getName();
    }

    /**
     * return module provider class name
     *
     * @return string
     */
    public function provider(): string
    {
        return $this->classPosition->getProviderClassName();
    }

    /**
     * return module directory path
     *
     * @return string
     */
    public function directory(string ...$directories): string
    {
        return $this->classPosition->getPath(...$directories);
    }

    /**
     * return installed file path
     *
     * @return string
     */
    public function installedPath(): string
    {
        return $this->directory(self::InstalledFilename);
    }

    /**
     * change installed
     *
     * @return self
     */
    public function installed(): self
    {
        $this->installed = true;

        return $this;
    }

    /**
     * change uninstalled
     *
     * @return self
     */
    public function uninstalled(): self
    {
        $this->installed = false;

        return $this;
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
