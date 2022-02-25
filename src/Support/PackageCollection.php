<?php

namespace Takemo101\SimpleModule\Support;

/**
 * php package collection class
 */
final class PackageCollection
{
    /**
     * @var Package[]
     */
    private array $collection = [];

    /**
     * constructor
     *
     * @param Package ...$packages
     */
    public function __construct(Package ...$packages)
    {
        foreach ($packages as $package) {
            $this->add($package);
        }
    }

    /**
     * add package
     *
     * @param Package $package
     * @return self
     */
    public function add(Package $package): self
    {
        $this->collection[$package->getPackage()] = $package;

        return $this;
    }

    /**
     * get require package names
     *
     * @return string[]
     */
    public function toRequirePackageNames(): array
    {
        return array_keys($this->collection);
    }

    /**
     * get remove package names
     *
     * @return string[]
     */
    public function toRemovePackageNames(): array
    {
        /**
         * @var string[]
         */
        $result = [];

        foreach ($this->collection as $package) {
            if ($package->isRemove()) {
                $result[] = $package->getPackage();
            }
        }

        return $result;
    }

    /**
     * constructor from array
     *
     * @param Package[] $packages
     * @return self
     */
    public static function fromArray(array $packages): self
    {
        return new self(...$packages);
    }

    /**
     * constructor from key and uninnstall set array
     *
     * @param boolean[] $packages
     * @return self
     */
    public static function fromSetArray(array $packages): self
    {
        /**
         * @var Package[]
         */
        $result = [];

        foreach ($packages as $name => $remove) {
            $result[] = new Package($name, $remove);
        }

        return self::fromArray($result);
    }
}
