<?php

namespace Takemo101\SimpleModule\Console;

use Illuminate\Console\Command;
use Takemo101\SimpleModule\Support\ManagerInterface as Manager;

class UpdateModuleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'simple-module:update {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simple-module update';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Manager $manager)
    {
        $module = $this->option('module');
        $module = is_array($module) ? $module[0] : $module;

        $manager->update($module);

        $this->info("successful simple-module update");
    }
}
