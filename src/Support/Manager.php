<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\{
    Arr,
    ServiceProvider as LaravelServiceProvider,
};
use Illuminate\Filesystem\Filesystem;

class Manager implements ManagerContract
{
    protected $app;
    protected $config;
    protected $files;

    protected $modules = [];
    protected $isLoaded = false;

    public function __construct($app, array $config = [], ?Filesystem $files = null)
    {
        $this->app = $app;
        $this->config = $config;
        $this->files = $files ?? new Filesystem;

        $this->loadModule();
    }

    /**
     * module provider load
     */
    public function load()
    {
        if ($this->isLoaded()) {
            return;
        }

        $providers = $this->installedModuleProviders();

        if (count($providers)) {
            (new ProviderRepository($this->app, $this->files, $this->cachedPath()))->load($providers);
        }

        $this->isLoaded = true;
    }

    /**
     * return cache path
     */
    public function cachedPath(): string
    {
        return $this->app->bootstrapPath(Arr::get($this->config, 'cache_path', 'cache/modules.php'));
    }

    /**
     * is load
     */
    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }

    /**
     * return installed module metas
     */
    protected function installedModules(): array
    {
        $modules = $this->modules();

        $result = [];
        foreach ($modules as $name => $module) {
            if ($module->isInstalled()) {
                $result[$name] = $module;
            }
        }

        return $result;
    }

    /**
     * return uninstalled module metas
     */
    protected function uninstalledModules(): array
    {
        $modules = $this->modules();

        $result = [];
        foreach ($modules as $name => $module) {
            if (!$module->isInstalled()) {
                $result[$name] = $module;
            }
        }

        return $result;
    }

    /**
     * return installed module provider class names
     */
    protected function installedModuleProviders(): array
    {
        $modules = $this->modules();

        $result = [];
        foreach ($modules as $name => $module) {
            if ($module->isInstalled()) {
                $result[$name] = $module->provider();
            }
        }

        return $result;
    }

    /**
     * return uninstalled module provider class names
     */
    protected function uninstalledModuleProviders(): array
    {
        $modules = $this->modules();

        $result = [];
        foreach ($modules as $name => $module) {
            if (!$module->isInstalled()) {
                $result[$name] = $module->provider();
            }
        }

        return $result;
    }

    /**
     * return all module metas
     */
    public function modules(): array
    {
        return $this->modules;
    }

    /**
     * load module metas
     */
    protected function loadModule()
    {
        $directory = Arr::get($this->config, 'directory');
        $filename = Arr::get($this->config, 'filename');
        $namespace = Arr::get($this->config, 'namespace');
        $deny = Arr::get($this->config, 'deny');
        $namespaceDirectories = Arr::get($this->config, 'submodule', []);

        $namespaceDirectories[$namespace] = $directory;

        $modules = [];
        $needDependencyNames = [];
        $needDependencyModules = [];

        foreach ($namespaceDirectories as $namespace => $directory) {
            if ($this->files->isDirectory($directory)) {
                $directories = $this->files->directories($directory);
                foreach ($directories as $dir) {
                    $name = $this->files->name($dir);

                    if ($provider = $this->findModuleProvider($name, $filename, $namespace, $deny)) {
                        $meta = new Meta($name, $provider, $dir, $this->files);

                        if ($dependencyModule = $provider::dependencyModule()) {
                            // not exists dependency module is continue
                            if (!isset($modules[$dependencyModule])) {
                                $needDependencyNames[$name] = $dependencyModule;
                                $needDependencyModules[$name] = $meta;
                                continue;
                            }
                        }
                        $modules[$name] = $meta;
                    }
                }

                $modules = $this->checkDependency($modules, $needDependencyNames, $needDependencyModules);
            }
        }

        $this->modules = $modules;
    }

    /**
     * check recursive dependency
     */
    protected function checkDependency(array $modules, array $needDependencyNames, array $needDependencyModules): array
    {
        $count = 0;
        foreach ($needDependencyNames as $name => $dependency) {
            if (isset($modules[$dependency])) {
                $modules[$name] = $needDependencyModules[$name];
                unset($needDependencyNames[$name], $needDependencyModules[$name]);
                $count++;
            }
        }
        if ($count > 0) {
            $modules = $this->checkDependency($modules, $needDependencyNames, $needDependencyModules);
        }

        return $modules;
    }

    /**
     * find module provider and create module meta
     */
    protected function findModuleProvider(string $name, string $filename, string $namespace, array $deny = []): ?string
    {
        $provider = $this->createModuleProviderClassName($name, $filename, $namespace);

        if (class_exists($provider)) {
            if (!in_array($provider, $deny)) {
                return $provider;
            }
        }

        return null;
    }

    /**
     * create module provider class name
     */
    protected function createModuleProviderClassName(string $name, string $filename, string $namespace)
    {
        return implode('\\', [$namespace, $name, $filename]);
    }

    /**
     * module install
     */
    public function install(?string $name = null)
    {
        $modules = $this->uninstalledModules();

        if ($name) {
            $modules = isset($modules[$name]) ? [$modules[$name]] : [];
        }

        foreach ($modules as $module) {
            $instance = $this->resolveModuleProvider($module);

            if ($instance instanceof ServiceProvider) {
                $instance->autoPackageRequire();
            }
            if ($instance instanceof InstallerInterface) {
                $instance->install();
            }

            $module->installed();
        }
    }

    /**
     * module uninstall
     */
    public function uninstall(?string $name = null)
    {
        $modules = $this->installedModules();

        if ($name) {
            $modules = isset($modules[$name]) ? [$modules[$name]] : [];
        }

        foreach ($modules as $module) {
            $instance = $this->resolveModuleProvider($module);

            if ($instance instanceof InstallerInterface) {
                $instance->uninstall();
            }
            if ($instance instanceof ServiceProvider) {
                $instance->autoPackageRemove();
            }

            $module->uninstalled();
        }
    }

    /**
     * resolve module provider instance by module meta
     */
    protected function resolveModuleProvider(Meta $module): LaravelServiceProvider
    {
        $provider = $module->provider();
        $providers = $this->app->getLoadedProviders();
        return $this->app->register($provider);
    }
}
