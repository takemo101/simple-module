<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

/**
 * module meta factory class
 */
final class MetaFactory
{
    /**
     * constructor
     *
     * @param Filesystem $files
     */
    public function __construct(
        private Filesystem $files,
    ) {
        //
    }

    /**
     * factory
     *
     * @param ModuleClassPosition $classPosition
     * @return Meta
     */
    public function factory(
        ModuleClassPosition $classPosition,
    ): Meta {
        return new Meta(
            $classPosition,
            $this->files->exists(
                $classPosition->getPath(Meta::InstalledFilename),
            ),
        );
    }
}
