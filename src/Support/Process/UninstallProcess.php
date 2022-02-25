<?php

namespace Takemo101\SimpleModule\Support\Process;

use Takemo101\SimpleModule\Support\{
    Composer,
    ServiceProvider as ModuleServiceProvider,
    InstallerInterface,
    PackageCollection,
};
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

/**
 * execute uninstall process
 */
class UninstallProcess
{
    /**
     * constructor
     *
     * @param Composer $composer
     */
    public function __construct(
        private Composer $composer,
    ) {
        //
    }

    /**
     * execute uninstall process
     *
     * @param ServiceProvider $instance
     * @return string
     */
    public function execute(ServiceProvider $instance): string
    {
        $output = '';

        if ($instance instanceof InstallerInterface) {
            $instance->uninstall();
        }

        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $packageNames = $packages->toRequirePackageNames();

            if (count($packageNames)) {
                $process = $this->composer->remove($packageNames);
                $process->run();
                $output = $process->getOutput();
            }
        }

        return $output;
    }
}
