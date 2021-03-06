<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\MetaBox;
use CEKW\WpPluginFramework\Core\ContentType\MetaBoxProvider;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\MetaBoxProvider
 */
class MetaBoxProviderTest extends MockeryTestCase
{
    public function testSomething(){
        $testProvider = new MetaBoxProvider('test');

        $metaBox = MetaBox::create('','','',false);

        $testProvider->_addMetaBox($metaBox);

        $this->assertSame([$metaBox],$testProvider->getMetaBoxes());
    }
}