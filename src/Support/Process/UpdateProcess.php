<?php

namespace Takemo101\SimpleModule\Support\Process;

use Takemo101\SimpleModule\Support\{
    Composer,
    ServiceProvider as ModuleServiceProvider,
    PackageCollection,
};
use Illuminate\Support\ServiceProvider;

/**
 * execute update process
 */
class UpdateProcess
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
        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $this->composer->update($packages->toRequirePackageNames());
        }
    }
}
