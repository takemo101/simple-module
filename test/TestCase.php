<?php

namespace Test;

use Takemo101\SimpleModule\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $directory = dirname(__DIR__, 1);

        $app['config']->set(
            'simple-module',
            [
                'directory' => "{$directory}/module",
                'namespace' => 'Module',
                'classname' => 'Module',
                'cache' => 'cache/modules.php',
                'denies' => ['Sync'],
                'modules' => [
                    'Other' => "{$directory}/other",
                ],
            ]
        );
    }
}
