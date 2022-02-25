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
     * @return string
     */
    public function execute(ServiceProvider $instance): string
    {
        $output = '';

        if ($instance instanceof ModuleServiceProvider) {
            $packages = PackageCollection::fromSetArray($instance->packages());
            $packageNames = $packages->toRequirePackageNames();

            if (count($packageNames)) {
                $process = $this->composer->require($packageNames);
                $process->run();
                $output = $process->getOutput();
            }
        }

        if ($instance instanceof InstallerInterface) {
            $instance->install();
        }

        return $output;
    }
}
