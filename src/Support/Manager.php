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
     * @param Composer $composer
     * @param Filesystem|null $files
     */
    public function __construct(
        private Application $app,
        private ModuleConfig $config,
        private Composer $composer,
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
     * @return string
     */
    public function install(?string $name = null): string
    {
        $output = '';

        $installProcess = new InstallProcess(
            $this->composer,
        );

        $collection = MetaCollection::toNotInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $output .= "\n----- start [{$meta->name()}] update -----\n\n";
            $output .= $installProcess->execute($this->resolveModuleProvider($meta));

            $meta->installed();
            $this->files->put($meta->installedPath(), 'installed');
        }

        return $output;
    }

    /**
     * module update
     *
     * @return string
     */
    public function update(?string $name = null): string
    {
        $output = '';

        $updateProcess = new UpdateProcess(
            $this->composer,
        );

        $collection = MetaCollection::toInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $output .= "\n----- start [{$meta->name()}] update -----\n\n";
            $output .= $updateProcess->execute($this->resolveModuleProvider($meta));
        }

        return $output;
    }

    /**
     * module uninstall
     *
     * @return string
     */
    public function uninstall(?string $name = null): string
    {
        $output = '';

        $uninstallProcess = new UninstallProcess(
            $this->composer,
        );

        $collection = MetaCollection::toInstalledCollection(
            $this->getMetaData(),
        );

        $metas = $collection->iteratorByName($name);

        foreach ($metas as $meta) {
            $output .= "\n----- start [{$meta->name()}] uninstall -----\n\n";
            $output .= $uninstallProcess->execute($this->resolveModuleProvider($meta));

            $meta->installed();
            $this->files->delete($meta->installedPath());
        }

        return $output;
    }

    /**
     * resolve module provider instance by module meta
     */
    protected function resolveModuleProvider(Meta $meta): LaravelServiceProvider
    {
        return $this->app->register($meta->provider());
    }
}
