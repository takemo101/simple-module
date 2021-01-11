<?php

namespace Takemo101\SimpleModule\Support;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    public function require(string $package, array $options = [])
    {
        $command = array_merge($this->findComposer(), array_merge(['require', $package], $options));
        $this->getProcess($command)->run();
    }

    public function remove(string $package, array $options = [])
    {
        $command = array_merge($this->findComposer(), array_merge(['remove', $package], $options));
        $this->getProcess($command)->run();
    }
}
