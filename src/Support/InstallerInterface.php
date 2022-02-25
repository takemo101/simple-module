<?php

namespace Takemo101\SimpleModule\Support;

interface InstallerInterface
{
    /**
     * module install process
     *
     * @return void
     */
    public function install();

    /**
     * module uninstall process
     *
     * @return void
     */
    public function uninstall();
}
