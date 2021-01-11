<?php

namespace Takemo101\SimpleModule;

use Illuminate\Support\ServiceProvider;
use Takemo101\SimpleModule\Support\ {
    ManagerContract,
    Manager,
    Creator,
    Composer,
};
use Takemo101\SimpleModule\Console\ {
    CreateModuleCommand,
    InstallModuleCommand,
    UninstallModuleCommand
};

class SimpleModuleServiceProvider extends ServiceProvider
{
    protected $config = 'simple-module';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__
            . '/../config/' . $this->config . '.php' => config_path($this->config . '.php'),
        ]);

        // modules load
        $this->app['simple-module.manager']->load();

        $this->registerConsoleCommands();
    }

    protected function registerConsoleCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            CreateModuleCommand::class,
            InstallModuleCommand::class,
            UninstallModuleCommand::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__
            . '/../config/' . $this->config . '.php',
            $this->config
        );

        $this->app->singleton('simple-module.manager', function ($app) {
            return new Manager(
                $app,
                $app['config'][$this->config],
                $app['files']
            );
        });
        $this->app->singleton('simple-module.composer', function ($app) {
            return new Composer($app['files'], $app->basePath());
        });
        $this->app->bind('simple-module.creator', function ($app) {
            $stub = __DIR__ . '/../stub/module.create.stub';
            return new Creator(
                $stub,
                $app['config'][$this->config],
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
}
