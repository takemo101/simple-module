<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Foundation\AliasLoader;
use ReflectionClass;

class ServiceProvider extends LaravelServiceProvider
{
    protected $dir;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->dir = dirname((new ReflectionClass(static::class))->getFileName());
    }

    protected function loadFacadesFrom(array $facades)
    {
        $loader = AliasLoader::getInstance();

        foreach($facades as $class => $alias) {
            $loader->alias($class, $alias);
        }

        return $this;
    }

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
            // 'package_name' => true or false
            // true is require and remove
            // false is require only
        ];
    }

    public function autoPackageRequire()
    {
        $packages = array_keys($this->packages());
        if (count($packages)) {
            $this->composerRequire($packages);
        }
    }

    public function autoPackageRemove()
    {
        $packages = Arr::where($this->packages(), function($v, $k) {
            return $v;
        });
        $packages = array_keys($packages);

        if (count($packages)) {
            $this->composerRemove($packages);
        }
    }

    /**
     * return module path
     */
    public function path(string $path = '')
    {
        $separator = DIRECTORY_SEPARATOR;
        $path = ltrim($path, $separator);
        return $path ? "{$this->dir}{$separator}{$path}" : $this->dir;
    }
}
