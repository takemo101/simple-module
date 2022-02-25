<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Filesystem\Filesystem;

/**
 * module meta loader class
 */
final class MetaLoader
{
    /**
     * @var MetaFactory
     */
    private MetaFactory $factory;

    public function __construct(
        private ModuleConfig $config,
        private Filesystem $files,
    ) {
        $this->factory = new MetaFactory($this->files);
    }

    public function load(): MetaCollection
    {
        $positions = $this->config->getPositions();

        /**
         * @var Meta[]
         */
        $modules = [];

        /**
         * @var string[]
         */
        $needDependencyNames = [];

        /**
         * @var Meta[]
         */
        $needDependencyModules = [];

        foreach ($positions->iterator() as $position) {
            $directory = $position->getPath();

            if ($this->files->isDirectory($directory)) {
                $directories = $this->files->directories($directory);

                foreach ($directories as $dir) {
                    $name = $this->files->name($dir);

                    $classPosition = new ModuleClassPosition(
                        $name,
                        $this->config->getClassName(),
                        $position,
                    );

                    if ($provider = $this->findModuleProvider($classPosition)) {
                        $meta = $this->factory->factory($classPosition);

                        if ($dependencyModule = $provider::dependencyModule()) {

                            // not exists dependency module is continue
                            if (!array_key_exists($dependencyModule, $modules)) {

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

        return MetaCollection::fromArray($modules);
    }

    /**
     * find module provider
     *
     * @param ModuleClassPosition $classPosition
     * @return string|null
     */
    private function findModuleProvider(ModuleClassPosition $classPosition): ?string
    {
        $provider = $classPosition->getProviderClassName();

        if (class_exists($provider)) {
            if (
                !$this->config->inDenies($classPosition->getNamespace()) &&
                !$this->config->inDenies($classPosition->getName())
            ) {
                return $provider;
            }
        }

        return null;
    }

    /**
     * check recursive dependency
     *
     * @param Meta[] $modules
     * @param string[] $needDependencyNames
     * @param Meta[] $needDependencyModules
     * @return Meta[]
     */
    private function checkDependency(array $modules, array $needDependencyNames, array $needDependencyModules): array
    {
        $count = 0;
        foreach ($needDependencyNames as $name => $dependency) {
            if (array_key_exists($dependency, $modules)) {

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
}
