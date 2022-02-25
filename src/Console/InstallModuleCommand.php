<?php

namespace Takemo101\SimpleModule\Console;

use Illuminate\Console\Command;
use Takemo101\SimpleModule\Support\ManagerContract as Manager;

class InstallModuleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'simple-module:install {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simple-module installed';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Manager $manager)
    {
        $module = $this->option('module');
        $module = is_array($module) ? $module[0] : $module;

        $output = $manager->install($module);

        $this->line($output);

        $this->info("successful simple-module installed");
    }
}
