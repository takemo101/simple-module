<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected function composerRequire($package, array $options = [])
    {
        $this->app['simple-module.composer']->require($package, $options);
    }

    protected function composerRemove($package, array $options = [])
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
        $packages = array_unique($this->packages());
        if (count($packages)) {
            $this->composerRequire($packages);
        }
    }

    public function autoPackageRemove()
    {
        $packages = array_unique($this->packages());
        if (count($packages)) {
            $this->composerRemove($packages);
        }
    }
}
