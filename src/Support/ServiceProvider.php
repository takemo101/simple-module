<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\AliasLoader;
use ReflectionClass;

/**
 * module service provider class
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * @var string|null
     */
    public static $dependencyModule = null; // dependency module name

    /**
     * @var string
     */
    protected $baseDirectory;

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->baseDirectory = dirname((string)(new ReflectionClass(static::class))->getFileName());
    }

    /**
     * load facades
     *
     * @param string[] $facades
     * @return self
     */
    protected function loadFacadesFrom(array $facades): self
    {
        $loader = AliasLoader::getInstance();

        foreach ($facades as $class => $alias) {
            $loader->alias($class, $alias);
        }

        return $this;
    }

    /**
     * package set
     *
     * [ 'package-name' => true or false ]
     * true is require and remove
     * false is require only
     *
     * @return boolean[]
     */
    public function packages(): array
    {
        return [
            // add composer packages
            // 'package_name' => true or false
            // true is require and remove
            // false is require only
        ];
    }

    /**
     * get module path
     *
     * @param string ...$paths
     * @return string
     */
    public function path(string ...$paths): string
    {
        $separator = DIRECTORY_SEPARATOR;

        return implode($separator, [
            $this->baseDirectory,
            ...array_filter(
                array_map(fn (string $p): string => trim($p, $separator), $paths)
            ),
        ]);
    }

    /**
     * get dependency module
     *
     * @return string|null
     */
    public static function dependencyModule(): ?string
    {
        return static::$dependencyModule;
    }
}
