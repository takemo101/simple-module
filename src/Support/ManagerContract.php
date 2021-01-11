<?php

namespace Takemo101\SimpleModule\Support;

interface ManagerContract
{
    /**
     * load Module Provider
     */
    public function load();

    /**
     * return cache path
     */
    public function cachedPath() : string;

    /**
     * is load
     */
    public function isLoaded() : bool;

    /**
     * module install
     */
    public function install(?string $name = null);

    /**
     * module uninstall
     */
    public function uninstall(?string $name = null);
}
