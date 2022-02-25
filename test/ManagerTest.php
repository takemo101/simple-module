<?php

namespace Test;

use Takemo101\SimpleModule\Support\{
    ManagerContract,
    Manager,
    MetaCollection,
};
use Illuminate\Filesystem\Filesystem;

class ManagerTest extends TestCase
{
    /**
     * @test
     */
    public function getInstalledProviders__OK(): void
    {
        /**
         * @var Manager
         */
        $manager = $this->app[ManagerContract::class];
        $collection = $manager->getMetaData();

        $metas = $collection->iteratorByName();

        $this->assertEquals(count($metas), 2);

        $installedCollection = MetaCollection::toInstalledCollection($collection);
        $metas = $installedCollection->iteratorByName();

        $this->assertEquals(count($metas), 1);

        $notInstalledCollection = MetaCollection::toNotInstalledCollection($collection);
        $metas = $notInstalledCollection->iteratorByName();

        $this->assertEquals(count($metas), 1);
    }

    /**
     * @test
     */
    public function getMetaDTOs__OK(): void
    {
        /**
         * @var Manager
         */
        $manager = $this->app[ManagerContract::class];
        $metadtos = $manager->modules();

        $this->assertEquals(count($metadtos), 2);
    }
}
