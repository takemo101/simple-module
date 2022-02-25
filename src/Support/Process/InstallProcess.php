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
 * execute install process
 */
class InstallProcess
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
     * execute install process
     *
     * @param ServiceProvider $instance
     * @return void
     */
    public function execute(ServiceProvider $instance): void
    {
        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $this->composer->require($packages->toRequirePackageNames());
        }

        if ($instance instanceof InstallerInterface) {
            $instance->install();
        }
    }
}
