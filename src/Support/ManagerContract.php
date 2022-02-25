<?php

namespace Takemo101\SimpleModule\Support;

/**
 * module manager interface
 */
interface ManagerContract
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
     * @return string
     */
    public function install(?string $name = null): string;

    /**
     * module update
     *
     * @param string|null $name
     * @return string
     */
    public function update(?string $name = null): string;

    /**
     * module uninstall
     *
     * @param string|null $name
     * @return string
     */
    public function uninstall(?string $name = null): string;
}
