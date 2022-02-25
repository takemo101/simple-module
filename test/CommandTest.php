<?php

namespace Test;

use Takemo101\SimpleModule\Console\{
    CreateModuleCommand,
    InstallModuleCommand,
    UninstallModuleCommand,
    UpdateModuleCommand,
};
use Illuminate\Filesystem\Filesystem;

class CommandTest extends TestCase
{
    /**
     * @var string
     */
    private string $baseDirectory = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseDirectory = dirname(__DIR__, 1);
    }

    /**
     * @test
     */
    public function executeCreateCommand__OK(): void
    {
        $this->artisan(CreateModuleCommand::class, [
            'name' => 'Simple',
        ])
            ->assertExitCode(0);

        /**
         * @var Filesystem
         */
        $filesystem = $this->app[Filesystem::class];

        $filesystem->deleteDirectory("{$this->baseDirectory}/module/Simple");
    }

    /**
     * @test
     */
    public function executeInstallCommand__OK(): void
    {
        /**
         * @var Filesystem
         */
        $filesystem = $this->app[Filesystem::class];

        $this->artisan(InstallModuleCommand::class, [
            '--module' => 'Type',
        ])
            ->assertExitCode(0);

        $this->assertTrue(
            $filesystem->exists("{$this->baseDirectory}/other/Type/installed"),
        );

        $this->artisan(UpdateModuleCommand::class, [
            '--module' => 'Type',
        ])
            ->assertExitCode(0);

        $this->artisan(UninstallModuleCommand::class, [
            '--module' => 'Type',
        ])
            ->assertExitCode(0);

        $this->assertFalse(
            $filesystem->exists("{$this->baseDirectory}/other/Type/installed"),
        );
    }
}
