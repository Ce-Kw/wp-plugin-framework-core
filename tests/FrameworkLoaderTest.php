<?php

namespace CEKW\WpPluginFramework\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\FrameworkLoader
 */
class FrameworkLoaderTest extends MockeryTestCase
{
    public function testDummy(){
        $this->assertSame(true,true);
    }
}