<?php

namespace Takemo101\SimpleModule;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Takemo101\SimpleModule\Support\{
    ManagerContract,
    Manager,
    ModuleConfig,
    Creator,
    Composer,
};
use Takemo101\SimpleModule\Console\{
    CacheModuleCommand,
    CreateModuleCommand,
    InstallModuleCommand,
    UpdateModuleCommand,
    UninstallModuleCommand
};

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @var string
     */
    protected $config = 'simple-module';

    /**
     * @var string
     */
    protected $baseDirectory;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->baseDirectory = dirname(__DIR__, 1);
    }

    public function boot(): void
    {
        $this->publishes([
            "{$this->baseDirectory}/config/{$this->config}.php" => $this->app->configPath("{$this->config}.php"),
            $this->config,
        ]);

        // load modules
        /**
         * @var ManagerContract
         */
        $manager = $this->app['simple-module.manager'];
        $manager->load();

        $this->registerConsoleCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            "{$this->baseDirectory}/config/{$this->config}.php",
            $this->config
        );
        $this->app->singleton('simple-module.config', function ($app) {
            return ModuleConfig::fromConfigArray($app['config'][$this->config]);
        });
        $this->app->singleton('simple-module.manager', function ($app) {
            return new Manager(
                $app,
                $app['simple-module.config'],
                $app['files']
            );
        });
        $this->app->singleton('simple-module.composer', function ($app) {
            return new Composer($app['files'], $app->basePath());
        });
        $this->app->bind('simple-module.creator', function ($app) {
            $stub = "{$this->baseDirectory}/stub/module.create.stub";

            return new Creator(
                $stub,
                $app['simple-module.config'],
                $app['files']
            );
        });

        foreach ([
            'simple-module.manager' => ManagerContract::class,
            'simple-module.creator' => Creator::class,
        ] as $key => $alias) {
            $this->app->alias($key, $alias);
        }
    }

    /**
     * register command
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            CreateModuleCommand::class,
            InstallModuleCommand::class,
            UpdateModuleCommand::class,
            UninstallModuleCommand::class,
        ]);
    }
}
