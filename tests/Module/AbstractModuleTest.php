<?php

namespace CEKW\WpPluginFramework\Tests\Module;

use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\Module\AbstractModule
 */
class AbstractModuleTest extends MockeryTestCase
{
    public function testDummy(){
        $this->assertSame(true,true);
    }
}