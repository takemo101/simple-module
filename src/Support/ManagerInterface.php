<?php

namespace Takemo101\SimpleModule\Support;

/**
 * module manager interface
 */
interface ManagerInterface
{
    /**
     * module provider load
     *
     * @return void
     */
    public function load(): void;

    /**
     * module install
     *
     * @param string|null $name
     * @return void
     */
    public function install(?string $name = null): void;

    /**
     * module update
     *
     * @param string|null $name
     * @return void
     */
    public function update(?string $name = null): void;

    /**
     * module uninstall
     *
     * @param string|null $name
     * @return void
     */
    public function uninstall(?string $name = null): void;
}
