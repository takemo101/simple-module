<?php

namespace Takemo101\SimpleModule\Support;

interface InstallerInterface
{
    /**
     * module provider install
     */
    public function install();

    /**
     * module provider uninstall
     */
    public function uninstall();
}
