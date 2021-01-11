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
    protected $signature = 'simple-module:create {name}';

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

        $creator->create($name);

        $this->info("successful [{$name}] simple-module creation");
    }
}
