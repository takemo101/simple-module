<?php

namespace Other\Sync;

use Takemo101\SimpleModule\Support\ {
    InstallerInterface,
    ServiceProvider,
};

class Module extends ServiceProvider implements InstallerInterface
{
    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }

    /**
     * module install process
     *
     * @return void
     */
    public function install()
    {
        //
    }

    /**
     * module uninstall process
     *
     * @return void
     */
    public function uninstall()
    {
        //
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
}
