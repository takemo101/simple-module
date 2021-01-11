<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Filesystem\Filesystem;

class Meta
{
    const INSTALLED_FILENAME = 'installed';

    protected $name, $provider, $directory;
    protected $files;

    public function __construct(string $name, string $provider, string $directory, Filesystem $files)
    {
        $this->name = $name;
        $this->provider = $provider;
        $this->directory = $directory;
        $this->files = $files;
    }

    /**
     * return module name
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * return module provider class name
     */
    public function provider() : string
    {
        return $this->provider;
    }

    /**
     * return module directory path
     */
    public function directory()
    {
        return $this->directory;
    }

    /**
     * return installed file path
     */
    public function installedPath()
    {
        return $this->directory . DIRECTORY_SEPARATOR . self::INSTALLED_FILENAME;
    }


    /**
     * exists installed file
     */
    public function isInstalled() : bool
    {
        return $this->files->exists($this->installedPath());
    }

    /**
     * put installed file
     */
    public function installed()
    {
        $this->files->put($this->installedPath(), 'installed');
    }

    /**
     * delete installed file
     */
    public function uninstalled()
    {
        $this->files->delete($this->installedPath());
    }
}
