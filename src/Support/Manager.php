<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Foundation\ProviderRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Takemo101\SimpleModule\Support\Process\{
    InstallProcess,
    UninstallProcess,
    UpdateProcess,
};

/**
 * module manager class
 */
final class Manager implements ManagerContract
{
    /**
     * @var Filesystem
     */
    private Filesystem $files;

    /**
     * @var MetaLoader
     */
    private MetaLoader $loader;

    /**
     * @var MetaCollection|null
     */
    private $metaCollection = null;

    /**
     * @var boolean
     */
    private $loaded = false;

    /**
     * constructor
     *
     * @param Application $app
     * @param ModuleConfig $config
     * @param Filesystem|null $files
     */
    public function __construct(
        private Application $app,
        private ModuleConfig $config,
        ?Filesystem $files = null
    ) {
        $this->files = $files ?? new Filesystem;

        $this->loader = new MetaLoader(
            $this->config,
            $this->files,
        );
    }

    /**
     * module provider load
     *
     * @return void
     */
    public function load(): void
    {
        if ($this->loaded) {
            return;
        }

        $providers = MetaCollection::toInstalledCollection(
            $this->getMetaData(),
        )->getProviders();

        if (count($providers)) {
            (new ProviderRepository($this->app, $this->files, $this->getCachePath()))->load($providers);
        }

        $this->loaded = true;
    }

    /**
     * get cache path
     *
     * @return string
     */
    private function getCachePath(): string
    {
        return $this->app->bootstrapPath($this->config->getCachePath());
    }

    /**
     * get module meta collection
     *
     * @return MetaCollection
     */
    public function getMetaData(): MetaCollection
    {
        if (!$this->metaCollection) {
            $this->metaCollection = $this->loader->load();
        }

        return $this->metaCollection;
    }

    /**
     * get module meta dto array
     *
     * @return MetaDTO[]
     */
    public function modules(): array
    {
        return $this->getMetaData()->toMetaDTOs();
    }

    /**
     * module install
     *
     * @return void
     */
    public function install(?string $name = null): void
    {
        $installProcess = new InstallProcess(
            $this->app['simple-module.composer'],
        );

        $collection = MetaCollection::toNotInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $installProcess->execute($this->resolveModuleProvider($meta));

            $meta->installed();
            $this->files->put($meta->installedPath(), 'installed');
        }
    }

    /**
     * module update
     *
     * @return void
     */
    public function update(?string $name = null): void
    {
        $updateProcess = new UpdateProcess(
            $this->app['simple-module.composer'],
        );

        $collection = MetaCollection::toInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $updateProcess->execute($this->resolveModuleProvider($meta));
        }
    }

    /**
     * module uninstall
     *
     * @return void
     */
    public function uninstall(?string $name = null): void
    {
        $uninstallProcess = new UninstallProcess(
            $this->app['simple-module.composer'],
        );

        $collection = MetaCollection::toInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $uninstallProcess->execute($this->resolveModuleProvider($meta));

            $meta->installed();
            $this->files->delete($meta->installedPath());
        }
    }

    /**
     * resolve module provider instance by module meta
     */
    protected function resolveModuleProvider(Meta $meta): LaravelServiceProvider
    {
        return $this->app->register($meta->provider());
    }
}
