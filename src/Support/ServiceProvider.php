<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected function composerRequire(string $package, array $options = [])
    {
        $this->app['simple-module.composer']->require($package, $options);
    }

    protected function composerRemove(string $package, array $options = [])
    {
        $this->app['simple-module.composer']->remove($package);
    }

    public function packages() : array
    {
        return [
            // add composer packages
        ];
    }

    public function autoPackageRequire()
    {
        $packages = $this->packages();
        foreach($packages as $package) {
            $this->composerRequire($package);
        }
    }

    public function autoPackageRemove()
    {
        $packages = $this->packages();
        foreach($packages as $package) {
            $this->composerRemove($package);
        }
    }
}
