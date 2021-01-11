<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    public function require($package, array $options = [])
    {
        $command = $this->createPackageCommand('require', $package);
        $command = array_merge($this->findComposer(), array_merge($command, $options));
        $this->getProcess($command)->run();
    }

    public function remove($package, array $options = [])
    {
        $command = $this->createPackageCommand('remove', $package);
        $command = array_merge($this->findComposer(), array_merge($command, $options));
        $this->getProcess($command)->run();
    }

    protected function createPackageCommand(string $command, $package) : array
    {
        $packages = is_array($package) ? $package : [$package];
        array_unshift($packages, $command);
        return $packages;
    }
}
