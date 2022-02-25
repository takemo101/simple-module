<?php

namespace Takemo101\SimpleModule\Support\Process;

use Takemo101\SimpleModule\Support\{
    Composer,
    ServiceProvider as ModuleServiceProvider,
    PackageCollection,
};
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

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
     * @return string
     */
    public function execute(ServiceProvider $instance): string
    {
        $output = '';

        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $packageNames = $packages->toRequirePackageNames();

            if (count($packageNames)) {
                $process = $this->composer->update($packageNames);
                $process->run();
                $output = $process->getOutput();
            }
        }

        return $output;
    }
}
