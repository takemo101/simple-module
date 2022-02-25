<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;

/**
 * stub create class
 */
final class Creator
{
    /**
     * @var string
     */
    private string $stubPath;

    /**
     * @var ModuleConfig
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * constructor
     *
     * @param string $stubPath
     * @param ModuleConfig $config
     * @param Filesystem|null $files
     * @throws InvalidArgumentException
     */
    public function __construct(string $stubPath, ModuleConfig $config, ?Filesystem $files = null)
    {
        $this->files = $files ?? new Filesystem;

        if (!$this->files->exists($stubPath)) {
            throw new InvalidArgumentException("file not found error: stub path is [{$stubPath}]");
        }

        $this->stubPath = $stubPath;
        $this->config = $config;
    }

    /**
     * create module file
     *
     * @param string $name
     * @param null|string $target
     * @return string
     */
    public function create(string $name, ?string $target = null): string
    {
        $position = $this->config->getPosition();
        $classname = $this->config->getClassName();

        if ($target) {
            if ($position = $this->config->getPositions()->findByNamespace($target)) {
                $position = $position;
            }
        }

        $namespace = $position->getNamespace();
        $stub = $this->files->get($this->stubPath);

        foreach (compact('classname', 'namespace', 'name') as $key => $value) {
            $stub = str_replace(
                ['{{ ' . $key . ' }}', '{{' . $key . '}}'],
                $value,
                $stub
            );
        }

        $directory = $position->getPath($name);
        $filePath = $position->getPath($name, "{$classname}.php");

        $this->files->ensureDirectoryExists($directory, 0755, true);
        $this->files->put($filePath, $stub);

        return $filePath;
    }
}
