<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Filesystem\Filesystem;

class Creator
{
    protected $stub;
    protected $config;
    protected $files;

    public function __construct(string $stub, array $config = [], ?Filesystem $files = null)
    {
        $this->stub = $stub;
        $this->config = $config;
        $this->files = $files ?? new Filesystem;

        if (!$this->files->exists($this->stub)) {
            throw new \Exception("[{$this->stub}] file not found");
        }
    }

    public function create(string $name)
    {
        extract($this->config);

        $stub = $this->files->get($this->stub);

        foreach (compact('filename', 'namespace', 'name') as $key => $value) {
            $stub = str_replace(
                ['{{ ' . $key . ' }}', '{{' . $key . '}}'],
                $value,
                $stub
            );
        }

        $sperator = DIRECTORY_SEPARATOR;
        $dirPath = "{$directory}{$sperator}{$name}";
        $filePath = "{$dirPath}{$sperator}{$filename}.php";

        $this->files->makeDirectory($dirPath, 0755, true);
        $this->files->put($filePath, $stub);

        return $filePath;
    }
}
