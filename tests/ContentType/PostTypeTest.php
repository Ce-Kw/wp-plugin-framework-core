<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\PostType
 * @covers \CEKW\WpPluginFramework\Core\ContentType\LabelInfo
 * @covers \CEKW\WpPluginFramework\Core\AbstractExtenderBridge
 */
class PostTypeTest extends MockeryTestCase
{
    private PostType $postType;

    public function setUp(): void
    {
        $this->postType = new PostType();
    }

    public function testSetGetIsPublic(): void
    {
        $testValue = true;
        $this->postType->setIsPublic($testValue);
        $this->assertSame($testValue, $this->postType->getIsPublic());
    }

    public function testSetKey(): void
    {
        $testValue = '';
        $this->assertSame($testValue, $this->postType->getKey());
    }

    public function testGetArgs(): void
    {
        $this->assertArrayHasKey('public',$this->postType->getArgs());
        $this->assertArrayHasKey('supports',$this->postType->getArgs());
        $this->assertArrayHasKey('labels',$this->postType->getArgs());
    }

    public function testSetGetLabelInnfo():void{
        $testLabelName = 'Labelname';
        $this->postType->setLabelName($testLabelName);

        $this->assertSame($testLabelName, $this->postType->getLabelName());
    }

}