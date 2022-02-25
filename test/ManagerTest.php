<?php

namespace Test;

use Takemo101\SimpleModule\Support\{
    ManagerInterface,
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
        $manager = $this->app[ManagerInterface::class];
        $collection = $manager->getModules();

        $metas = $collection->iteratorByName();

        $this->assertEquals(count($metas), 2);

        $installedCollection = MetaCollection::toInstalledCollection($collection);
        $metas = $installedCollection->iteratorByName();

        $this->assertEquals(count($metas), 1);

        $notInstalledCollection = MetaCollection::toNotInstalledCollection($collection);
        $metas = $notInstalledCollection->iteratorByName();

        $this->assertEquals(count($metas), 1);
    }
}
