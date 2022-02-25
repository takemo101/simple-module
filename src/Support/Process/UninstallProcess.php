<?php

namespace Takemo101\SimpleModule\Support\Process;

use Takemo101\SimpleModule\Support\{
    Composer,
    ServiceProvider as ModuleServiceProvider,
    InstallerInterface,
    PackageCollection,
};
use Illuminate\Support\ServiceProvider;

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
     * @return void
     */
    public function execute(ServiceProvider $instance): void
    {
        if ($instance instanceof InstallerInterface) {
            $instance->uninstall();
        }

        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $this->composer->remove($packages->toRemovePackageNames());
        }
    }
}
