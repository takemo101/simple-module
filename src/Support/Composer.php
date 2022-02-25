<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Composer as BaseComposer;
use Symfony\Component\Process\Process;

/**
 * execute composer comannd class
 */
class Composer extends BaseComposer
{
    /**
     * execute composer require command
     *
     * @param string[]|string $package
     * @param string[] $options
     * @return Process
     */
    public function require(array|string $package, array $options = []): Process
    {
        $command = $this->createPackageCommand('require', $package);
        $command = array_merge($this->findComposer(), array_merge($command, $options));
        return $this->getProcess($command);
    }

    /**
     * execute composer update command
     *
     * @param string[]|string $package
     * @param string[] $options
     * @return Process
     */
    public function update(array|string $package, array $options = []): Process
    {
        $command = $this->createPackageCommand('update', $package);
        $command = array_merge($this->findComposer(), array_merge($command, $options));
        return $this->getProcess($command);
    }

    /**
     * execute composer remove command
     *
     * @param string[]|string $package
     * @param string[] $options
     * @return Process
     */
    public function remove(array|string $package, array $options = []): Process
    {
        $command = $this->createPackageCommand('remove', $package);
        $command = array_merge($this->findComposer(), array_merge($command, $options));
        return $this->getProcess($command);
    }

    /**
     * create packages string
     *
     * @param string $command
     * @param string[]|string $package
     * @return string[]
     */
    protected function createPackageCommand(string $command, array|string $package): array
    {
        $packages = is_array($package) ? $package : [$package];
        array_unshift($packages, $command);
        return $packages;
    }
}
