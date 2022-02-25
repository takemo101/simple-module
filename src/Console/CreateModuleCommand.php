<?php

namespace Takemo101\SimpleModule\Console;

use Illuminate\Console\Command;
use Takemo101\SimpleModule\Support\Creator;

class CreateModuleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'simple-module:create {name} {--namespace=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simple-module creation';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Creator $creator)
    {
        $name = $this->argument('name');
        $name = is_array($name) ? $name[0] : (string)$name;

        $namespace = $this->option('namespace');
        $namespace = is_array($namespace) ? $namespace[0] : $namespace;

        $creator->create($name, $namespace);

        $this->info("successful [{$name}] simple-module creation");
    }
}
