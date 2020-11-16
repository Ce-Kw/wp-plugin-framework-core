<?php

namespace CEKW\WpPluginFramework\Tests\Package;

use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\Package\AbstractPackage
 */
class AbstractPackageTest extends MockeryTestCase
{
    public function testDummy(){
        $this->assertSame(true,true);
    }
}